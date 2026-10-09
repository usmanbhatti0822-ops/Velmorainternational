<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Product;
use App\Models\SupplyPackage;
use Database\Seeders\VelmoraCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_the_default_locale_and_home_displays_divisions(): void
    {
        $this->seed(VelmoraCatalogSeeder::class);

        $response = $this->get('/');

        $response->assertRedirect('/en');
        $this->get('/en')
            ->assertOk()
            ->assertSee('From trusted sourcing to your next shipment.')
            ->assertSee('Textile & Garments')
            ->assertSee('Built for every buyer')
            ->assertSee('Support for businesses at every stage.')
            ->assertSee('/admin/login')
            ->assertDontSee('site.buyer_types');
    }

    public function test_arabic_home_uses_rtl_and_translated_division_names(): void
    {
        $this->seed(VelmoraCatalogSeeder::class);

        $this->get('/ar')
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('الأرز')
            ->assertSee('حلول لكل مشترٍ')
            ->assertSee('دعم للأعمال في جميع مراحلها.')
            ->assertDontSee('site.flexible_supply');
    }

    public function test_only_published_products_are_accessible(): void
    {
        $this->seed(VelmoraCatalogSeeder::class);
        $division = Division::query()->firstOrFail();
        $product = Product::query()->create([
            'division_id' => $division->id,
            'slug' => 'sample-product',
            'name' => ['en' => 'Sample product', 'ar' => 'منتج تجريبي'],
            'is_active' => false,
        ]);

        $this->get('/en/products/sample-product')->assertNotFound();
        $product->update(['is_active' => true]);
        $this->get('/en/products/sample-product')->assertOk()->assertSee('Sample product');
    }

    public function test_sitemap_is_valid_xml_and_contains_localized_alternates(): void
    {
        $this->seed(VelmoraCatalogSeeder::class);

        $response = $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('hreflang="x-default"', false)
            ->assertSee('/en/products', false)
            ->assertSee('/ar/products', false);

        $document = new \DOMDocument;
        $this->assertTrue($document->loadXML($response->getContent()));
    }

    public function test_supply_package_cards_expand_with_details_and_prefill_the_quote_request(): void
    {
        $this->seed(VelmoraCatalogSeeder::class);
        $package = SupplyPackage::query()->where('slug', 'sample')->firstOrFail();

        $this->get('/en/supply-packages')
            ->assertOk()
            ->assertSee('<dialog', false)
            ->assertSee('aria-haspopup="dialog"', false)
            ->assertSee('View package details')
            ->assertSee('Details to confirm together')
            ->assertSee('Product selection and buyer specifications')
            ->assertSee('For importers and purchasing teams who want to assess product suitability.')
            ->assertSee('Back to packages')
            ->assertSee('package=sample', false);

        $quoteResponse = $this->get('/en/request-a-quote?package=sample')->assertOk();
        $this->assertMatchesRegularExpression(
            '/supply_package_id.{0,40}'.preg_quote((string) $package->id, '/').'/',
            $quoteResponse->getContent(),
        );
    }

    public function test_arabic_supply_package_details_are_translated_and_offer_a_quote_link(): void
    {
        $this->seed(VelmoraCatalogSeeder::class);

        $this->get('/ar/supply-packages')
            ->assertOk()
            ->assertSee('<dialog', false)
            ->assertSee('عرض تفاصيل الباقة')
            ->assertSee('تفاصيل نؤكدها معاً')
            ->assertSee('اختيار المنتج ومواصفات المشتري')
            ->assertSee('للمستوردين وفرق الشراء الراغبة في تقييم ملاءمة المنتج.')
            ->assertSee('العودة إلى الباقات')
            ->assertSee('اطلب هذا الخيار');
    }
}
