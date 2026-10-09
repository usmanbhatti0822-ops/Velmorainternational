<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Division;
use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_multi_product_quote_request_is_saved_with_reference_and_private_attachment(): void
    {
        Mail::fake();
        Storage::fake('local');
        $this->seed(\Database\Seeders\VelmoraCatalogSeeder::class);
        $division = Division::query()->firstOrFail();
        $product = Product::query()->create([
            'division_id' => $division->id,
            'slug' => 'rice-product',
            'name' => ['en' => 'Rice', 'ar' => 'أرز'],
            'is_active' => true,
        ]);

        $response = $this->post('/en/request-a-quote', [
            'name' => 'Buyer',
            'company' => 'Import Co',
            'email' => 'BUYER@example.com',
            'country_code' => 'AE',
            'destination_port' => 'Jebel Ali',
            'items' => [
                ['product_id' => $product->id, 'quantity' => '20', 'unit' => 'MT'],
                ['product_name_text' => 'Unlisted grain', 'quantity' => '5', 'unit' => 'MT'],
            ],
            'attachments' => [UploadedFile::fake()->create('specification.pdf', 25, 'application/pdf')],
        ]);

        $response->assertRedirect(route('inquiries.create', ['locale' => 'en']));
        $response->assertSessionHas('reference');
        $inquiry = Inquiry::query()->with('items')->firstOrFail();
        $this->assertSame('VEL-'.now()->format('Y').'-'.str_pad((string) $inquiry->id, 5, '0', STR_PAD_LEFT), $inquiry->reference);
        $this->assertSame('buyer@example.com', $inquiry->contact->email);
        $this->assertCount(2, $inquiry->items);
        $this->assertSame(1, $inquiry->attachments()->count());
        Storage::disk('local')->assertExists($inquiry->attachments()->firstOrFail()->path);
    }

    public function test_honeypot_blocks_automated_quote_submission(): void
    {
        $this->from('/en/request-a-quote')->post('/en/request-a-quote', [
            'website' => 'spam.example',
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'items' => [['product_name_text' => 'Product']],
        ])->assertSessionHasErrors('website');

        $this->assertSame(0, Inquiry::query()->count());
        $this->assertSame(0, Contact::query()->count());
    }
}
