<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Certification;
use App\Models\ChatConversation;
use App\Models\ContactMessage;
use App\Models\Division;
use App\Models\Inquiry;
use App\Models\PackagingOption;
use App\Models\Product;
use App\Models\Setting;
use App\Models\SupplyPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin', 'sales', 'content_manager', 'chat_agent']);

        return view('admin.dashboard', [
            'newInquiries' => Inquiry::query()->where('status', 'new')->count(),
            'openConversations' => ChatConversation::query()->whereIn('status', ['waiting', 'active', 'offline'])->count(),
            'unreadMessages' => ChatConversation::query()->sum('unread_agent_count'),
            'productsCount' => Product::query()->where('is_active', true)->count(),
            'recentInquiries' => Inquiry::query()->with('contact')->latest()->limit(6)->get(),
            'recentConversations' => ChatConversation::query()->with('contact')->latest('last_message_at')->limit(6)->get(),
        ]);
    }

    public function inquiries(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin', 'sales']);

        return view('admin.inquiries.index', [
            'inquiries' => Inquiry::query()->with(['contact', 'items.product'])->latest()->paginate(20),
        ]);
    }

    public function exportInquiries(Request $request): StreamedResponse
    {
        $this->authorizeRoles($request, ['super_admin', 'sales']);

        return response()->streamDownload(function (): void {
            $output = fopen('php://output', 'w');

            if ($output === false) {
                throw new \RuntimeException('Unable to create inquiry export.');
            }

            fputcsv($output, ['Reference', 'Created at', 'Name', 'Email', 'Company', 'Country', 'Status', 'Products', 'Destination port']);
            Inquiry::query()->with(['contact', 'items'])->orderBy('id')->chunkById(500, function ($inquiries) use ($output): void {
                foreach ($inquiries as $inquiry) {
                    $safe = static function (?string $value): string {
                        $value = (string) $value;

                        return preg_match('/^[\s]*[=+\-@]/u', $value) === 1 ? "'".$value : $value;
                    };
                    fputcsv($output, [
                        $safe($inquiry->reference),
                        $inquiry->created_at?->toIso8601String() ?? '',
                        $safe($inquiry->contact->name),
                        $safe($inquiry->contact->email),
                        $safe($inquiry->contact->company),
                        $safe($inquiry->contact->country_code),
                        $safe($inquiry->status),
                        $safe($inquiry->items->pluck('product_name_text')->filter()->implode('; ')),
                        $safe($inquiry->destination_port),
                    ]);
                }
            });

            fclose($output);
        }, 'velmora-inquiries.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function contactMessages(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin', 'sales']);

        return view('admin.contact-messages', [
            'messages' => ContactMessage::query()->latest()->paginate(25),
        ]);
    }

    public function settings(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin']);
        $setting = Setting::query()->firstOrCreate(
            ['key' => 'certifications_visible'],
            ['value' => ['enabled' => false], 'group' => 'content'],
        );

        return view('admin.settings', [
            'certificationsVisible' => (bool) data_get($setting->value, 'enabled', false),
            'certifications' => Certification::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin']);
        $validated = $request->validate(['certifications_visible' => ['nullable', 'boolean']]);
        Setting::query()->updateOrCreate(
            ['key' => 'certifications_visible'],
            ['value' => ['enabled' => (bool) ($validated['certifications_visible'] ?? false)], 'group' => 'content'],
        );

        return back()->with('status', 'Content visibility settings saved.');
    }

    public function storeCertification(Request $request): RedirectResponse
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $validated = $request->validate([
            'name_en' => ['required', 'string', 'max:180'],
            'name_ar' => ['nullable', 'string', 'max:180'],
            'issuer' => ['nullable', 'string', 'max:180'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        $filePath = $request->file('file')?->store('certifications', 'public');
        $logoPath = $request->file('logo')?->store('certifications/logos', 'public');

        if (($request->hasFile('file') && $filePath === false) || ($request->hasFile('logo') && $logoPath === false)) {
            throw new \RuntimeException('The certification files could not be stored.');
        }

        Certification::query()->create([
            'name' => ['en' => $validated['name_en'], 'ar' => $validated['name_ar'] ?? $validated['name_en']],
            'issuer' => $validated['issuer'] ?? null,
            'file_path' => $filePath,
            'logo_path' => $logoPath,
            'is_visible' => false,
        ]);

        return back()->with('status', 'Certification saved as hidden until the visibility switch is enabled.');
    }

    public function updateCertification(Request $request, Certification $certification): RedirectResponse
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $validated = $request->validate([
            'name_en' => ['required', 'string', 'max:180'],
            'name_ar' => ['nullable', 'string', 'max:180'],
            'issuer' => ['nullable', 'string', 'max:180'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        $filePath = $request->file('file')?->store('certifications', 'public');
        $logoPath = $request->file('logo')?->store('certifications/logos', 'public');

        if (($request->hasFile('file') && $filePath === false) || ($request->hasFile('logo') && $logoPath === false)) {
            throw new \RuntimeException('The certification files could not be stored.');
        }

        $certification->update([
            'name' => ['en' => $validated['name_en'], 'ar' => $validated['name_ar'] ?? $validated['name_en']],
            'issuer' => $validated['issuer'] ?? null,
            'file_path' => $filePath ?: $certification->file_path,
            'logo_path' => $logoPath ?: $certification->logo_path,
        ]);

        return back()->with('status', 'Certification details updated.');
    }

    public function updateCertificationVisibility(Request $request, Certification $certification): RedirectResponse
    {
        $this->authorizeRoles($request, ['super_admin']);
        $validated = $request->validate([
            'is_visible' => ['required', 'boolean'],
        ]);
        $certification->update(['is_visible' => $validated['is_visible']]);

        return back()->with('status', 'Certification visibility updated.');
    }

    public function updateInquiry(Request $request, Inquiry $inquiry)
    {
        $this->authorizeRoles($request, ['super_admin', 'sales']);
        $validated = $request->validate([
            'status' => ['required', Rule::in(['new', 'contacted', 'quoted', 'negotiating', 'won', 'lost', 'spam'])],
            'priority' => ['required', Rule::in(['low', 'normal', 'high'])],
            'note' => ['nullable', 'string', 'max:3000'],
        ]);

        DB::transaction(function () use ($request, $inquiry, $validated): void {
            $inquiry->update([
                'status' => $validated['status'],
                'priority' => $validated['priority'],
                'quoted_at' => $validated['status'] === 'quoted' ? ($inquiry->quoted_at ?? now()) : $inquiry->quoted_at,
                'closed_at' => in_array($validated['status'], ['won', 'lost', 'spam'], true) ? ($inquiry->closed_at ?? now()) : null,
            ]);

            if (! empty($validated['note'])) {
                $inquiry->notes()->create(['user_id' => $request->user()->id, 'body' => $validated['note']]);
            }
        });

        return back()->with('status', 'Inquiry updated.');
    }

    public function products(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);

        return view('admin.products.index', [
            'products' => Product::query()->with('division')->withTrashed()->latest()->paginate(20),
        ]);
    }

    public function content(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);

        return view('admin.content', [
            'divisions' => Division::query()->orderBy('sort_order')->get(),
            'packages' => SupplyPackage::query()->orderBy('sort_order')->get(),
            'packagingOptions' => PackagingOption::query()->orderBy('id')->get(),
            'catalogs' => Catalog::query()->with('division')->latest()->get(),
        ]);
    }

    public function updateDivision(Request $request, Division $division)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $validated = $request->validate([
            'name_en' => ['required', 'string', 'max:180'],
            'name_ar' => ['nullable', 'string', 'max:180'],
            'summary_en' => ['nullable', 'string', 'max:500'],
            'summary_ar' => ['nullable', 'string', 'max:500'],
            'description_en' => ['nullable', 'string', 'max:10000'],
            'description_ar' => ['nullable', 'string', 'max:10000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'is_active' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);
        $imagePath = $request->file('cover_image')?->store('divisions', 'public');

        if ($request->hasFile('cover_image') && $imagePath === false) {
            throw new \RuntimeException('The division image could not be stored.');
        }

        $division->update([
            'name' => ['en' => $validated['name_en'], 'ar' => $validated['name_ar'] ?? $validated['name_en']],
            'summary' => ['en' => $validated['summary_en'] ?? '', 'ar' => $validated['summary_ar'] ?? $validated['summary_en'] ?? ''],
            'description' => ['en' => $validated['description_en'] ?? '', 'ar' => $validated['description_ar'] ?? $validated['description_en'] ?? ''],
            'sort_order' => $validated['sort_order'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'cover_image' => $imagePath ?: $division->cover_image,
        ]);

        return back()->with('status', 'Division updated.');
    }

    public function updateSupplyPackage(Request $request, SupplyPackage $package)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $validated = $request->validate([
            'name_en' => ['required', 'string', 'max:180'],
            'name_ar' => ['nullable', 'string', 'max:180'],
            'audience_en' => ['nullable', 'string', 'max:500'],
            'audience_ar' => ['nullable', 'string', 'max:500'],
            'description_en' => ['nullable', 'string', 'max:5000'],
            'description_ar' => ['nullable', 'string', 'max:5000'],
            'min_qty_note_en' => ['nullable', 'string', 'max:500'],
            'min_qty_note_ar' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $package->update([
            'name' => ['en' => $validated['name_en'], 'ar' => $validated['name_ar'] ?? $validated['name_en']],
            'audience' => ['en' => $validated['audience_en'] ?? '', 'ar' => $validated['audience_ar'] ?? $validated['audience_en'] ?? ''],
            'description' => ['en' => $validated['description_en'] ?? '', 'ar' => $validated['description_ar'] ?? $validated['description_en'] ?? ''],
            'min_qty_note' => ['en' => $validated['min_qty_note_en'] ?? '', 'ar' => $validated['min_qty_note_ar'] ?? $validated['min_qty_note_en'] ?? ''],
            'sort_order' => $validated['sort_order'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return back()->with('status', 'Supply package updated.');
    }

    public function storePackagingOption(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $validated = $request->validate([
            'name_en' => ['required', 'string', 'max:180'],
            'name_ar' => ['nullable', 'string', 'max:180'],
            'kind' => ['required', Rule::in(['bag', 'carton', 'jar', 'pallet', 'bulk', 'container', 'custom'])],
            'size_value' => ['nullable', 'numeric', 'min:0'],
            'size_unit' => ['nullable', 'string', 'max:32'],
            'notes_en' => ['nullable', 'string', 'max:500'],
            'notes_ar' => ['nullable', 'string', 'max:500'],
        ]);

        PackagingOption::query()->create([
            'name' => ['en' => $validated['name_en'], 'ar' => $validated['name_ar'] ?? $validated['name_en']],
            'kind' => $validated['kind'],
            'size_value' => $validated['size_value'] ?? null,
            'size_unit' => $validated['size_unit'] ?? null,
            'notes' => ['en' => $validated['notes_en'] ?? '', 'ar' => $validated['notes_ar'] ?? $validated['notes_en'] ?? ''],
            'is_active' => true,
        ]);

        return back()->with('status', 'Packaging option added.');
    }

    public function storeCatalog(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $validated = $request->validate([
            'title_en' => ['required', 'string', 'max:180'],
            'title_ar' => ['nullable', 'string', 'max:180'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'requires_email' => ['nullable', 'boolean'],
        ]);
        $path = $request->file('file')->store('catalogs', 'public');

        if ($path === false) {
            throw new \RuntimeException('The catalog file could not be stored.');
        }

        Catalog::query()->create([
            'title' => ['en' => $validated['title_en'], 'ar' => $validated['title_ar'] ?? $validated['title_en']],
            'division_id' => $validated['division_id'] ?? null,
            'file_path' => $path,
            'requires_email' => (bool) ($validated['requires_email'] ?? false),
            'is_active' => true,
        ]);

        return back()->with('status', 'Catalog uploaded.');
    }

    public function updateCatalog(Request $request, Catalog $catalog)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $validated = $request->validate([
            'title_en' => ['required', 'string', 'max:180'],
            'title_ar' => ['nullable', 'string', 'max:180'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'requires_email' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $path = $request->file('file')?->store('catalogs', 'public');

        if ($request->hasFile('file') && $path === false) {
            throw new \RuntimeException('The catalog file could not be stored.');
        }

        $catalog->update([
            'title' => ['en' => $validated['title_en'], 'ar' => $validated['title_ar'] ?? $validated['title_en']],
            'division_id' => $validated['division_id'] ?? null,
            'file_path' => $path ?: $catalog->file_path,
            'requires_email' => (bool) ($validated['requires_email'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return back()->with('status', 'Catalog updated.');
    }

    public function createProduct(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);

        return view('admin.products.form', $this->productFormData(new Product));
    }

    public function storeProduct(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $validated = $this->validateProduct($request);

        $product = DB::transaction(function () use ($request, $validated): Product {
            $product = Product::query()->create($this->productAttributes($validated));
            $this->saveProductDetails($request, $product, $validated);

            return $product;
        });

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product saved.');
    }

    public function editProduct(Request $request, Product $product)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $product->load(['specs', 'images', 'packagingOptions', 'supplyPackages']);

        return view('admin.products.form', $this->productFormData($product));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $validated = $this->validateProduct($request, $product);

        DB::transaction(function () use ($request, $product, $validated): void {
            $product->update($this->productAttributes($validated));
            $product->specs()->delete();
            $this->saveProductDetails($request, $product, $validated);
        });

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product updated.');
    }

    public function deleteProduct(Request $request, Product $product)
    {
        $this->authorizeRoles($request, ['super_admin', 'content_manager']);
        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Product unpublished.');
    }

    public function conversations(Request $request)
    {
        $this->authorizeRoles($request, ['super_admin', 'sales', 'chat_agent']);

        return view('admin.conversations.index', [
            'conversations' => ChatConversation::query()->with(['contact', 'product'])
                ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
                ->latest('last_message_at')->paginate(20)->withQueryString(),
        ]);
    }

    public function conversation(Request $request, ChatConversation $conversation)
    {
        $this->authorizeRoles($request, ['super_admin', 'sales', 'chat_agent']);
        $conversation->update(['unread_agent_count' => 0]);
        $conversation->load(['contact', 'product', 'messages.attachments']);

        return view('admin.conversations.show', compact('conversation'));
    }

    public function reply(Request $request, ChatConversation $conversation)
    {
        $this->authorizeRoles($request, ['super_admin', 'sales', 'chat_agent']);
        abort_if($conversation->status === 'closed', 409, 'This conversation is closed.');
        $validated = $request->validate([
            'body' => ['nullable', 'string', 'max:2000', 'required_without:attachment'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf,docx,xlsx', 'max:10240'],
            'is_internal' => ['nullable', 'boolean'],
        ]);
        $isInternal = $request->boolean('is_internal');

        DB::transaction(function () use ($request, $conversation, $validated, $isInternal): void {
            $file = $request->file('attachment');
            $message = $conversation->messages()->create([
                'sender_type' => $isInternal ? 'system' : 'agent',
                'sender_id' => $request->user()->id,
                'body' => $validated['body'] ?? 'Shared a file.',
                'type' => $file ? 'file' : ($isInternal ? 'note' : 'text'),
                'is_internal' => $isInternal,
            ]);

            if ($file !== null) {
                $path = $file->store('chats/'.$conversation->id, 'local');

                if ($path === false) {
                    throw new \RuntimeException('The chat attachment could not be stored.');
                }

                $message->attachments()->create([
                    'disk' => 'local',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType() ?: 'application/octet-stream',
                    'size' => $file->getSize(),
                ]);
            }

            $conversation->update([
                'status' => 'active',
                'assigned_to' => $conversation->assigned_to ?? $request->user()->id,
                'first_response_at' => $conversation->first_response_at ?? now(),
                'last_message_at' => now(),
                'unread_visitor_count' => $isInternal ? $conversation->unread_visitor_count : $conversation->unread_visitor_count + 1,
            ]);
        });

        return back()->with('status', $isInternal ? 'Internal note added.' : 'Reply sent.');
    }

    public function updateConversation(Request $request, ChatConversation $conversation)
    {
        $this->authorizeRoles($request, ['super_admin', 'sales', 'chat_agent']);
        $validated = $request->validate(['status' => ['required', Rule::in(['waiting', 'active', 'offline', 'closed'])]]);
        $conversation->update([
            'status' => $validated['status'],
            'assigned_to' => $conversation->assigned_to ?? $request->user()->id,
            'closed_at' => $validated['status'] === 'closed' ? now() : null,
        ]);

        return back()->with('status', 'Conversation updated.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'slug' => ['required', 'alpha_dash', 'max:180', Rule::unique('products', 'slug')->ignore($product?->id)],
            'name_en' => ['required', 'string', 'max:180'],
            'name_ar' => ['nullable', 'string', 'max:180'],
            'short_description_en' => ['nullable', 'string', 'max:500'],
            'short_description_ar' => ['nullable', 'string', 'max:500'],
            'description_en' => ['nullable', 'string', 'max:10000'],
            'description_ar' => ['nullable', 'string', 'max:10000'],
            'origin' => ['nullable', 'string', 'max:120'],
            'moq_value' => ['nullable', 'numeric', 'min:0'],
            'moq_unit' => ['nullable', 'string', 'max:32'],
            'lead_time_days' => ['nullable', 'integer', 'between:1,3650'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'specs' => ['nullable', 'array', 'max:30'],
            'specs.*.key_en' => ['required_with:specs.*.value_en', 'nullable', 'string', 'max:100'],
            'specs.*.key_ar' => ['nullable', 'string', 'max:100'],
            'specs.*.value_en' => ['required_with:specs.*.key_en', 'nullable', 'string', 'max:255'],
            'specs.*.value_ar' => ['nullable', 'string', 'max:255'],
            'supply_packages' => ['nullable', 'array'],
            'supply_packages.*' => ['integer', 'exists:supply_packages,id'],
            'packaging_options' => ['nullable', 'array'],
            'packaging_options.*' => ['integer', 'exists:packaging_options,id'],
        ]);
    }

    private function productAttributes(array $validated): array
    {
        return [
            'division_id' => $validated['division_id'],
            'slug' => $validated['slug'],
            'name' => ['en' => $validated['name_en'], 'ar' => $validated['name_ar'] ?? $validated['name_en']],
            'short_description' => ['en' => $validated['short_description_en'] ?? '', 'ar' => $validated['short_description_ar'] ?? $validated['short_description_en'] ?? ''],
            'description' => ['en' => $validated['description_en'] ?? '', 'ar' => $validated['description_ar'] ?? $validated['description_en'] ?? ''],
            'origin' => $validated['origin'] ?? null,
            'moq_value' => $validated['moq_value'] ?? null,
            'moq_unit' => $validated['moq_unit'] ?? null,
            'lead_time_days' => $validated['lead_time_days'] ?? null,
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ];
    }

    private function saveProductDetails(Request $request, Product $product, array $validated): void
    {
        foreach ($validated['specs'] ?? [] as $index => $spec) {
            if (empty($spec['key_en']) || empty($spec['value_en'])) {
                continue;
            }

            $product->specs()->create([
                'spec_key' => ['en' => $spec['key_en'], 'ar' => $spec['key_ar'] ?? $spec['key_en']],
                'spec_value' => ['en' => $spec['value_en'], 'ar' => $spec['value_ar'] ?? $spec['value_en']],
                'sort_order' => $index,
            ]);
        }

        $product->supplyPackages()->sync($validated['supply_packages'] ?? []);
        $product->packagingOptions()->sync($validated['packaging_options'] ?? []);

        foreach ($request->file('images', []) as $index => $image) {
            $path = $image->store('products/'.$product->id, 'public');

            if ($path === false) {
                throw new \RuntimeException('The product image could not be stored.');
            }

            $product->images()->create([
                'path' => $path,
                'alt' => ['en' => $product->localized('name'), 'ar' => $product->localized('name', 'ar')],
                'is_primary' => $index === 0 && ! $product->images()->exists(),
                'sort_order' => $product->images()->count() + $index,
            ]);
        }
    }

    private function productFormData(Product $product): array
    {
        return [
            'product' => $product,
            'divisions' => Division::query()->orderBy('sort_order')->get(),
            'packages' => SupplyPackage::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'packagingOptions' => PackagingOption::query()->where('is_active', true)->orderBy('id')->get(),
        ];
    }

    private function authorizeRoles(Request $request, array $roles): void
    {
        abort_unless(in_array($request->user()->role, $roles, true), 403);
    }
}
