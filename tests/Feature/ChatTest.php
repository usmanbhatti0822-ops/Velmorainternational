<?php

namespace Tests\Feature;

use App\Models\ChatConversation;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_start_chat_and_resume_their_messages(): void
    {
        $response = $this->postJson('/chat/start', [
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'topic' => 'pricing',
            'locale' => 'en',
        ])->assertOk();
        $uuid = $response->json('uuid');
        $cookie = $response->getCookie('velmora_chat_'.$uuid);
        $this->assertNotNull($cookie);
        $conversation = ChatConversation::query()->where('uuid', $uuid)->firstOrFail();
        $this->assertSame($conversation->visitor_token, hash('sha256', $cookie->getValue()));

        $this->withCredentials()->withCookie('velmora_chat_'.$uuid, $cookie->getValue())
            ->postJson('/chat/'.$uuid.'/messages', ['message' => 'Please quote one container.'])
            ->assertCreated();

        $this->withCredentials()->withCookie('velmora_chat_'.$uuid, $cookie->getValue())
            ->getJson('/chat/'.$uuid.'/messages')
            ->assertOk()
            ->assertJsonPath('messages.1.body', 'Please quote one container.');
    }

    public function test_conversation_history_is_not_accessible_without_visitor_token(): void
    {
        $contact = Contact::query()->create(['name' => 'Private visitor', 'email' => 'private@example.com']);
        $conversation = ChatConversation::query()->create([
            'uuid' => (string) Str::uuid(),
            'contact_id' => $contact->id,
            'visitor_token' => hash('sha256', 'secret-token'),
            'status' => 'waiting',
        ]);

        $this->getJson('/chat/'.$conversation->uuid.'/messages')->assertNotFound();
    }

    public function test_offline_message_creates_a_flagged_conversation(): void
    {
        $response = $this->postJson('/chat/offline', [
            'name' => 'Offline visitor',
            'email' => 'offline@example.com',
            'message' => 'Please contact me about samples.',
        ])->assertOk()->assertJsonPath('status', 'offline');

        $conversation = ChatConversation::query()->where('uuid', $response->json('uuid'))->firstOrFail();
        $this->assertSame('offline', $conversation->status);
        $this->assertSame('Please contact me about samples.', $conversation->messages()->firstOrFail()->body);
    }
}
