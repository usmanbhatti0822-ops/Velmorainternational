<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->refreshDefaultDivisions(false);
        $this->refreshDefaultProducts(false);
    }

    public function down(): void
    {
        $this->refreshDefaultProducts(true);
        $this->refreshDefaultDivisions(true);
    }

    private function refreshDefaultDivisions(bool $reverse): void
    {
        $divisions = [
            [
                'slug' => 'rice',
                'old_name' => ['en' => 'Rice', 'ar' => 'الأرز'],
                'new_name' => ['en' => 'Rice & Food Grains', 'ar' => 'الأرز والحبوب الغذائية'],
                'old_summary' => 'Rice for importers, distributors and private-label buyers.',
                'new_summary' => 'Rice and selected food grains for international markets, subject to grade, quality and export requirements.',
            ],
            [
                'slug' => 'wheat',
                'old_name' => ['en' => 'Wheat', 'ar' => 'القمح'],
                'new_name' => ['en' => 'Wheat', 'ar' => 'القمح'],
                'old_summary' => 'Wheat and related products, supplied to confirmed specifications.',
                'new_summary' => 'Wheat and related products for domestic and international trade, subject to confirmed specifications.',
            ],
            [
                'slug' => 'grains',
                'old_name' => ['en' => 'Grains', 'ar' => 'الحبوب'],
                'new_name' => ['en' => 'Maize & Other Grains', 'ar' => 'الذرة والحبوب الأخرى'],
                'old_summary' => 'A flexible range of grains for international trade.',
                'new_summary' => 'Maize and other agricultural commodities for varied commercial applications.',
            ],
            [
                'slug' => 'dry-fruits',
                'old_name' => ['en' => 'Dry Fruits', 'ar' => 'الفواكه المجففة'],
                'new_name' => ['en' => 'Dry Fruits', 'ar' => 'الفواكه المجففة'],
                'old_summary' => 'Dry fruit selections prepared to buyer requirements.',
                'new_summary' => 'Dry fruit selections prepared to buyer requirements.',
            ],
            [
                'slug' => 'leather',
                'old_name' => ['en' => 'Leather', 'ar' => 'الجلود'],
                'new_name' => ['en' => 'Leather Products', 'ar' => 'المنتجات الجلدية'],
                'old_summary' => 'Leather goods and materials, subject to confirmed product availability.',
                'new_summary' => 'Leather products for fashion and selected practical uses, with styles and materials discussed per order.',
            ],
            [
                'slug' => 'textile-garments',
                'old_name' => ['en' => 'Textile & Garments', 'ar' => 'المنسوجات والملابس'],
                'new_name' => ['en' => 'Apparel & Home Textiles', 'ar' => 'الملابس والمنسوجات المنزلية'],
                'old_summary' => 'Textiles and garments with custom and private-label options.',
                'new_summary' => 'Apparel and home textile products with options for style, fabric and order specifications.',
            ],
        ];

        foreach ($divisions as $division) {
            $oldName = $reverse ? $division['new_name'] : $division['old_name'];
            $newName = $reverse ? $division['old_name'] : $division['new_name'];
            $oldSummary = $reverse ? $division['new_summary'] : $division['old_summary'];
            $newSummary = $reverse ? $division['old_summary'] : $division['new_summary'];

            DB::table('divisions')
                ->where('slug', $division['slug'])
                ->where('name->en', $oldName['en'])
                ->where('name->ar', $oldName['ar'])
                ->where('summary->en', $oldSummary)
                ->where('description->en', $oldSummary)
                ->update([
                    'name' => $this->encodeJson($newName),
                    'summary' => $this->encodeJson([
                        'en' => $newSummary,
                        'ar' => 'تواصل معنا لتأكيد المنتجات والمواصفات المتاحة.',
                    ]),
                    'description' => $this->encodeJson([
                        'en' => $newSummary,
                        'ar' => 'تواصل معنا لتأكيد المنتجات والمواصفات المتاحة.',
                    ]),
                ]);
        }
    }

    private function refreshDefaultProducts(bool $reverse): void
    {
        $demoNote = 'Illustrative demo listing. Origin, specifications, availability, pricing, packaging and order quantities are confirmed with the buyer before any offer.';
        $demoNoteArabic = 'منتج تجريبي توضيحي. يتم تأكيد المنشأ والمواصفات والتوفر والأسعار والتعبئة والكميات مع المشتري قبل تقديم أي عرض.';
        $products = [
            [
                'slug' => 'hard-wheat-grain',
                'old_summary' => [
                    'en' => 'A wheat sourcing enquiry for milling and food-industry buyers.',
                    'ar' => 'استفسار توريد قمح لمطاحن ومشتري الصناعات الغذائية.',
                ],
                'new_summary' => [
                    'en' => 'Hard wheat for milling and food-industry trade enquiries.',
                    'ar' => 'قمح صلب لاستفسارات التجارة الخاصة بالمطاحن والصناعات الغذائية.',
                ],
            ],
            [
                'slug' => 'yellow-corn',
                'old_summary' => [
                    'en' => 'A yellow corn sourcing option with quality parameters confirmed per enquiry.',
                    'ar' => 'خيار توريد ذرة صفراء مع تأكيد معايير الجودة حسب الاستفسار.',
                ],
                'new_summary' => [
                    'en' => 'Yellow corn for commercial trade, with quality parameters discussed per enquiry.',
                    'ar' => 'ذرة صفراء للتجارة، مع مناقشة معايير الجودة حسب الاستفسار.',
                ],
            ],
        ];

        foreach ($products as $product) {
            $oldSummary = $reverse ? $product['new_summary'] : $product['old_summary'];
            $newSummary = $reverse ? $product['old_summary'] : $product['new_summary'];

            DB::table('products')
                ->where('slug', $product['slug'])
                ->where('short_description->en', $oldSummary['en'])
                ->where('short_description->ar', $oldSummary['ar'])
                ->where('description->en', $oldSummary['en'].' '.$demoNote)
                ->where('description->ar', $oldSummary['ar'].' '.$demoNoteArabic)
                ->update([
                    'short_description' => $this->encodeJson($newSummary),
                    'description' => $this->encodeJson([
                        'en' => $newSummary['en'].' '.$demoNote,
                        'ar' => $newSummary['ar'].' '.$demoNoteArabic,
                    ]),
                ]);
        }
    }

    /**
     * @param  array<string, string>  $value
     */
    private function encodeJson(array $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
};
