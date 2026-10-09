<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\SupplyPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InquiryController extends Controller
{
    public function create(Request $request)
    {
        $packagePrefill = $request->filled('package')
            ? SupplyPackage::query()
                ->where('is_active', true)
                ->where('slug', $request->query('package'))
                ->value('id')
            : null;

        return view('pages.inquiries.create', [
            'products' => Product::query()->where('is_active', true)->orderBy('name')->get(),
            'prefill' => $request->query('product'),
            'packagePrefill' => $packagePrefill,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'website' => ['nullable', 'max:0'],
            'name' => ['required', 'string', 'max:160'],
            'company' => ['nullable', 'string', 'max:160'],
            'designation' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'country_code' => ['nullable', 'string', 'size:2'],
            'destination_port' => ['nullable', 'string', 'max:160'],
            'delivery_timeline' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1', 'max:12'],
            'items.*.product_id' => ['nullable', Rule::exists('products', 'id')->where('is_active', true)],
            'items.*.product_name_text' => ['nullable', 'string', 'max:255'],
            'items.*.quantity' => ['nullable', 'numeric', 'min:0.001', 'max:999999999'],
            'items.*.unit' => ['nullable', 'string', 'max:32'],
            'items.*.supply_package_id' => ['nullable', 'exists:supply_packages,id'],
            'items.*.packaging_option_id' => ['nullable', 'exists:packaging_options,id'],
            'items.*.notes' => ['nullable', 'string', 'max:1000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'marketing_opt_in' => ['nullable', 'boolean'],
        ]);

        foreach ($validated['items'] as $index => $item) {
            if (empty($item['product_id']) && empty(trim((string) ($item['product_name_text'] ?? '')))) {
                throw ValidationException::withMessages(["items.$index.product_name_text" => 'Choose a product or enter a product name.']);
            }
        }

        $inquiry = DB::transaction(function () use ($request, $validated): Inquiry {
            $email = strtolower($validated['email']);
            $contact = Contact::query()->firstOrNew(['email' => $email]);
            $contact->fill([
                'name' => $validated['name'],
                'company' => $validated['company'] ?? null,
                'designation' => $validated['designation'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'whatsapp' => $validated['whatsapp'] ?? null,
                'country_code' => isset($validated['country_code']) ? strtoupper($validated['country_code']) : null,
                'source' => 'rfq',
                'first_seen_at' => $contact->first_seen_at ?? now(),
                'last_seen_at' => now(),
                'marketing_opt_in' => $contact->marketing_opt_in || $request->boolean('marketing_opt_in'),
            ]);
            $contact->save();

            $inquiry = $contact->inquiries()->create([
                'status' => 'new',
                'destination_port' => $validated['destination_port'] ?? null,
                'delivery_timeline' => $validated['delivery_timeline'] ?? null,
                'message' => $validated['message'] ?? null,
                'locale' => app()->getLocale(),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 5000),
                'utm_source' => $request->query('utm_source'),
                'utm_medium' => $request->query('utm_medium'),
                'utm_campaign' => $request->query('utm_campaign'),
            ]);
            $inquiry->update(['reference' => \sprintf('VEL-%s-%05d', now()->format('Y'), $inquiry->id)]);

            foreach ($validated['items'] as $item) {
                $product = isset($item['product_id']) ? Product::query()->findOrFail($item['product_id']) : null;
                $inquiry->items()->create([
                    'product_id' => $product?->id,
                    'product_name_text' => $product?->localized('name') ?? ($item['product_name_text'] ?? null),
                    'quantity' => $item['quantity'] ?? null,
                    'unit' => $item['unit'] ?? null,
                    'supply_package_id' => $item['supply_package_id'] ?? null,
                    'packaging_option_id' => $item['packaging_option_id'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            foreach ($request->file('attachments', []) as $file) {
                $path = $file->store('inquiries/'.$inquiry->id, 'local');

                if ($path === false) {
                    throw new \RuntimeException('The inquiry attachment could not be stored.');
                }

                $inquiry->attachments()->create([
                    'disk' => 'local',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType() ?: 'application/octet-stream',
                    'size' => $file->getSize(),
                ]);
            }

            return $inquiry;
        });

        $salesEmail = (string) config('mail.from.address');
        Mail::raw('A new quote request '.$inquiry->reference.' has been received.', fn ($message) => $message->to($salesEmail)->subject('New Velmora quote request'));
        Mail::raw('Thank you for your inquiry. Your reference is '.$inquiry->reference.'. Our team will review your request.', fn ($message) => $message->to($inquiry->contact->email)->subject('Your Velmora quote request'));

        return redirect()->route('inquiries.create', ['locale' => app()->getLocale()])->with('reference', $inquiry->reference);
    }
}
