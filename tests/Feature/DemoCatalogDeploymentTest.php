<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DemoCatalogDeploymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_catalog_seeding_adds_missing_records_without_overwriting_existing_products(): void
    {
        Artisan::call('velmora:seed-demo-catalog');

        $this->assertSame(28, Product::query()->count());

        $product = Product::query()->firstOrFail();
        $product->update([
            'is_active' => false,
            'name' => ['en' => 'Admin-customized name', 'ar' => 'اسم مخصص'],
        ]);

        Artisan::call('velmora:seed-demo-catalog');

        $this->assertSame(28, Product::query()->count());
        $this->assertFalse($product->fresh()->is_active);
        $this->assertSame('Admin-customized name', $product->fresh()->name['en']);
    }
}
