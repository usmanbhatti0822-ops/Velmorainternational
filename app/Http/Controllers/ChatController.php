<?php

namespace App\Http\Controllers;

use App\Models\ChatAttachment;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Contact;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    public function start(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'topic' => ['nullable', 'in:product,pricing,samples,private-label,other'],
            'product_id' => ['nullable', 'exists:products,id'],
            'page_url' => ['nullable', 'url', 'max:2048'],
            'locale' => ['nullable', 'in:en,ar'],
        ]);

        if (! empty($validated['product_id'])) {
            if (! Product::query()->whereKey($validated['product_id'])->where('is_active', true)->exists()) {
                throw ValidationException::withMessages(['product_id' => 'The selected product is not available.']);
            }
        }

        $email = strtolower($validated['email']);
        $token = Str::random(64);
        $conversation = DB::transaction(function () use ($request, $validated, $email, $token): ChatConversation {
            $contact = Contact::query()->firstOrNew(['email' => $email]);
            $contact->fill([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'source' => 'chat',
                'first_seen_at' => $contact->first_seen_at ?? now(),
                'last_seen_at' => now(),
            ])->save();

            return ChatConversation::query()->create([
                'uuid' => (string) Str::uuid(),
                'contact_id' => $contact->id,
                'visitor_token' => hash('sha256', $token),
                'status' => 'waiting',
                'product_id' => $validated['product_id'] ?? null,
                'page_url' => $validated['page_url'] ?? null,
                'locale' => $validated['locale'] ?? 'en',
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 5000),
                'started_at' => now(),
                'last_message_at' => now(),
                'unread_agent_count' => 0,
            ]);
        });

        $conversation->messages()->create([
            'sender_type' => 'system',
            'body' => 'Thanks for contacting Velmora. A team member will reply here.',
            'type' => 'text',
        ]);

        return response()->json(['uuid' => $conversation->uuid, 'status' => $conversation->status])
            ->cookie('velmora_chat_'.$conversation->uuid, $token, 60 * 24 * 30, '/', null, $request->isSecure(), true, false, 'lax');
    }

    public function offline(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:2000'],
            'page_url' => ['nullable', 'url', 'max:2048'],
            'locale' => ['nullable', 'in:en,ar'],
        ]);

        $email = strtolower($validated['email']);
        $token = Str::random(64);
        $conversation = DB::transaction(function () use ($request, $validated, $email, $token): ChatConversation {
            $contact = Contact::query()->firstOrNew(['email' => $email]);
            $contact->fill([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'source' => 'chat',
                'first_seen_at' => $contact->first_seen_at ?? now(),
                'last_seen_at' => now(),
            ])->save();

            $conversation = ChatConversation::query()->create([
                'uuid' => (string) Str::uuid(),
                'contact_id' => $contact->id,
                'visitor_token' => hash('sha256', $token),
                'status' => 'offline',
                'page_url' => $validated['page_url'] ?? null,
                'locale' => $validated['locale'] ?? 'en',
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 5000),
                'started_at' => now(),
                'last_message_at' => now(),
            ]);
            $conversation->messages()->create(['sender_type' => 'visitor', 'body' => $validated['message'], 'type' => 'text']);

            return $conversation;
        });

        Mail::raw('An offline chat message was received from '.$email.'. Conversation '.$conversation->uuid.'.', fn ($mail) => $mail->to((string) config('mail.from.address'))->subject('New offline Velmora chat'));

        return response()->json(['uuid' => $conversation->uuid, 'status' => $conversation->status, 'message' => 'Your message has been saved.'])
            ->cookie('velmora_chat_'.$conversation->uuid, $token, 60 * 24 * 30, '/', null, $request->isSecure(), true, false, 'lax');
    }

    public function index(Request $request, string $uuid): JsonResponse
    {
        $conversation = $this->visitorConversation($request, $uuid);
        $conversation->update(['unread_visitor_count' => 0]);
        $after = $request->integer('after', 0);

        return response()->json([
            'status' => $conversation->status,
            'messages' => $conversation->messages()
                ->where('is_internal', false)
                ->where('id', '>', $after)
                ->with('attachments')
                ->orderBy('id')
                ->get()
                ->map(fn (ChatMessage $message) => [
                    'id' => $message->id,
                    'sender' => $message->sender_type,
                    'body' => $message->body,
                    'created_at' => $message->created_at?->toIso8601String(),
                    'attachments' => $message->attachments->map(fn (ChatAttachment $attachment) => [
                        'name' => $attachment->original_name,
                        'url' => route('chat.attachments.show', ['uuid' => $uuid, 'attachment' => $attachment->id]),
                    ]),
                ]),
        ]);
    }

    public function store(Request $request, string $uuid): JsonResponse
    {
        $conversation = $this->visitorConversation($request, $uuid);
        abort_if($conversation->status === 'closed', 409, 'This conversation is closed.');
        $validated = $request->validate(['message' => ['required', 'string', 'max:2000']]);

        $message = DB::transaction(function () use ($conversation, $validated): ChatMessage {
            $message = $conversation->messages()->create([
                'sender_type' => 'visitor',
                'body' => trim($validated['message']),
                'type' => 'text',
            ]);
            $conversation->update([
                'last_message_at' => now(),
                'unread_agent_count' => $conversation->unread_agent_count + 1,
                'status' => $conversation->status === 'offline' ? 'offline' : 'waiting',
            ]);

            return $message;
        });

        return response()->json(['id' => $message->id, 'created_at' => $message->created_at?->toIso8601String()], 201);
    }

    public function attach(Request $request, string $uuid): JsonResponse
    {
        $conversation = $this->visitorConversation($request, $uuid);
        abort_if($conversation->status === 'closed', 409, 'This conversation is closed.');
        $validated = $request->validate([
            'attachment' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf,docx,xlsx', 'max:10240'],
        ]);
        $file = $validated['attachment'];
        $path = $file->store('chats/'.$conversation->id, 'local');

        if ($path === false) {
            throw new \RuntimeException('The chat attachment could not be stored.');
        }

        $message = $conversation->messages()->create([
            'sender_type' => 'visitor',
            'body' => 'Shared a file.',
            'type' => 'file',
        ]);
        $message->attachments()->create([
            'disk' => 'local',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
        ]);

        $conversation->update(['last_message_at' => now(), 'unread_agent_count' => $conversation->unread_agent_count + 1]);

        return response()->json(['id' => $message->id], 201);
    }

    public function downloadAttachment(Request $request, string $uuid, ChatAttachment $attachment): StreamedResponse
    {
        $isStaff = (bool) ($request->user()?->is_active && in_array($request->user()->role, ['super_admin', 'sales', 'content_manager', 'chat_agent'], true));
        $conversation = $isStaff
            ? ChatConversation::query()->where('uuid', $uuid)->firstOrFail()
            : $this->visitorConversation($request, $uuid);

        $messageQuery = $attachment->message()->where('conversation_id', $conversation->id);

        if (! $isStaff) {
            $messageQuery->where('is_internal', false);
        }

        abort_unless($messageQuery->exists(), 404);

        return Storage::disk($attachment->disk)->download($attachment->path, $attachment->original_name);
    }

    public function rate(Request $request, string $uuid): JsonResponse
    {
        $conversation = $this->visitorConversation($request, $uuid);
        $validated = $request->validate(['rating' => ['required', 'integer', 'between:1,5'], 'comment' => ['nullable', 'string', 'max:1000']]);
        $conversation->update(['rating' => $validated['rating'], 'rating_comment' => $validated['comment'] ?? null]);

        return response()->json(['saved' => true]);
    }

    private function visitorConversation(Request $request, string $uuid): ChatConversation
    {
        $conversation = ChatConversation::query()->where('uuid', $uuid)->firstOrFail();
        $token = (string) $request->cookie('velmora_chat_'.$uuid, '');
        abort_unless($token !== '' && hash_equals($conversation->visitor_token, hash('sha256', $token)), 404);

        return $conversation;
    }
}
