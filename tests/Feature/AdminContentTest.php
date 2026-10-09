<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\SupplyPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

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
