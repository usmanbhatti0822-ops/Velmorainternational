<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Division;
use App\Models\Product;
use App\Models\SupplyPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_navigation_highlights_only_the_current_page(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'content_manager', 'is_active' => true]));

        $dashboard = $this->get('/admin')->assertOk()->getContent();
        $this->assertSame(1, preg_match('/<a\b(?=[^>]*href="[^"]*\/admin")(?=[^>]*aria-current="page")[^>]*>.*?Dashboard<\/a>/s', $dashboard));
        $this->assertSame(1, substr_count($dashboard, 'aria-current="page"'));

        $content = $this->get('/admin/content')->assertOk()->getContent();
        $this->assertSame(0, preg_match('/<a\b(?=[^>]*href="[^"]*\/admin")(?=[^>]*aria-current="page")[^>]*>.*?Dashboard<\/a>/s', $content));
        $this->assertSame(1, substr_count($content, 'aria-current="page"'));
        $this->assertStringContainsString('Content &amp; categories', $content);
    }

    public function test_content_manager_can_add_hide_and_restore_a_category_without_deleting_products(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'content_manager', 'is_active' => true]));
        $this->get('/admin/content')
            ->assertOk()
            ->assertSee('Add category');

        $this->post('/admin/divisions', [
            'name_en' => 'Leather Goods',
            'name_ar' => 'منتجات جلدية',
            'summary_en' => 'Leather products for buyers.',
            'summary_ar' => 'منتجات جلدية للمشترين.',
            'is_active' => 1,
        ])->assertRedirect()->assertSessionHasNoErrors();

        $division = Division::query()->where('slug', 'leather-goods')->firstOrFail();
        $product = Product::query()->create([
            'division_id' => $division->id,
            'slug' => 'leather-goods-sample',
            'name' => ['en' => 'Leather goods sample', 'ar' => 'عينة منتجات جلدية'],
            'is_active' => true,
        ]);
        $this->get('/en/divisions/leather-goods')->assertOk();

        $this->patch('/admin/divisions/'.$division->id.'/visibility', ['is_active' => 0])
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertFalse($division->fresh()->is_active);
        $this->assertTrue($product->fresh()->is_active);
        $this->get('/en/divisions/leather-goods')->assertNotFound();
        $this->get('/en/products/leather-goods-sample')->assertNotFound();

        $this->patch('/admin/divisions/'.$division->id.'/visibility', ['is_active' => 1])
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->get('/en/divisions/leather-goods')->assertOk();
    }

    public function test_super_admin_can_add_a_hidden_certification_and_control_its_visibility(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => 'super_admin', 'is_active' => true]));

        $this->post('/admin/certifications', [
            'name_en' => 'Approved certificate',
            'name_ar' => 'شهادة معتمدة',
            'issuer' => 'Client-approved issuer',
        ])->assertRedirect();

        $certification = Certification::query()->firstOrFail();
        $this->assertFalse($certification->is_visible);
        $this->get('/en/certifications')->assertNotFound();

        $this->patch('/admin/certifications/'.$certification->id.'/visibility', ['is_visible' => 1])
            ->assertSessionHasNoErrors();
        $this->patch('/admin/settings', ['certifications_visible' => 1])
            ->assertSessionHasNoErrors();

        $this->get('/en/certifications')->assertOk()->assertSee('Approved certificate');
    }

    public function test_content_manager_can_update_bilingual_supply_package_content(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'content_manager', 'is_active' => true]));
        $package = SupplyPackage::query()->create([
            'slug' => 'sample-package',
            'name' => ['en' => 'Original name', 'ar' => 'الاسم الأصلي'],
            'audience' => ['en' => 'Original audience', 'ar' => 'الفئة الأصلية'],
            'description' => ['en' => 'Original description', 'ar' => 'الوصف الأصلي'],
            'min_qty_note' => ['en' => 'Original minimum', 'ar' => 'الحد الأصلي'],
        ]);

        $this->get('/admin/content')
            ->assertOk()
            ->assertSee('Audience (Arabic)')
            ->assertSee('Minimum quantity note (Arabic)');

        $this->patch('/admin/supply-packages/'.$package->id, [
            'name_en' => 'Updated name',
            'name_ar' => 'الاسم المحدث',
            'audience_en' => 'Updated audience',
            'audience_ar' => 'الفئة المحدثة',
            'description_en' => 'Updated description',
            'description_ar' => 'الوصف المحدث',
            'min_qty_note_en' => 'Updated minimum',
            'min_qty_note_ar' => 'الحد المحدث',
            'sort_order' => 0,
            'is_active' => 1,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('supply_packages', [
            'id' => $package->id,
            'audience->ar' => 'الفئة المحدثة',
            'min_qty_note->ar' => 'الحد المحدث',
        ]);
    }

    public function test_sales_staff_cannot_change_certification_visibility(): void
    {
        $certification = Certification::query()->create([
            'name' => ['en' => 'Hidden certificate'],
            'is_visible' => false,
        ]);
        $this->actingAs(User::factory()->create(['role' => 'sales', 'is_active' => true]));

        $this->get('/admin/settings')->assertForbidden();
        $this->patch('/admin/certifications/'.$certification->id.'/visibility', ['is_visible' => 1])
            ->assertForbidden();

        $this->assertFalse($certification->fresh()->is_visible);
    }
}
