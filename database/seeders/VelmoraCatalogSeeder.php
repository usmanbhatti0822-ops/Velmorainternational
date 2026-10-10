<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\SupplyPackage;
use Illuminate\Database\Seeder;

class VelmoraCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['rice', 'Rice', 'الأرز', 'Rice for importers, distributors and private-label buyers.', 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=1200&q=85'],
            ['wheat', 'Wheat', 'القمح', 'Wheat and related products, supplied to confirmed specifications.', 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?auto=format&fit=crop&w=1200&q=85'],
            ['grains', 'Grains', 'الحبوب', 'A flexible range of grains for international trade.', 'https://images.unsplash.com/photo-1515543904379-3d757afe72e4?auto=format&fit=crop&w=1200&q=85'],
            ['dry-fruits', 'Dry Fruits', 'الفواكه المجففة', 'Dry fruit selections prepared to buyer requirements.', 'https://images.unsplash.com/photo-1596591606975-97ee5cef3a1e?auto=format&fit=crop&w=1200&q=85'],
            ['leather', 'Leather', 'الجلود', 'Leather goods and materials, subject to confirmed product availability.', 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=1200&q=85'],
            ['textile-garments', 'Textile & Garments', 'المنسوجات والملابس', 'Textiles and garments with custom and private-label options.', 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?auto=format&fit=crop&w=1200&q=85'],
        ];

        foreach ($divisions as $index => [$slug, $english, $arabic, $summary, $coverImage]) {
            Division::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => ['en' => $english, 'ar' => $arabic],
                    'summary' => ['en' => $summary, 'ar' => 'تواصل معنا لتأكيد المنتجات والمواصفات المتاحة.'],
                    'description' => ['en' => $summary, 'ar' => 'تواصل معنا لتأكيد المنتجات والمواصفات المتاحة.'],
                    'cover_image' => $coverImage,
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
        }

        $demoProducts = [
            ['rice', 'premium-basmati-rice', 'Premium Basmati Rice', 'أرز بسمتي فاخر', 'A long-grain rice option for wholesale, retail and private-label enquiries.', 'خيار أرز طويل الحبة لاستفسارات الجملة والتجزئة والعلامات التجارية الخاصة.', 'photo-1586201375761-83865001e31c'],
            ['rice', 'long-grain-white-rice', 'Long Grain White Rice', 'أرز أبيض طويل الحبة', 'A versatile white rice listing with buyer-led grade and packing requirements.', 'منتج أرز أبيض متعدد الاستخدامات مع درجات وتعبئة حسب طلب المشتري.', 'photo-1516684732162-798a0062be99'],
            ['rice', 'whole-grain-brown-rice', 'Whole Grain Brown Rice', 'أرز بني كامل الحبة', 'A whole-grain rice option for buyers developing retail or food-service ranges.', 'خيار أرز كامل الحبة للمشترين الذين يطورون منتجات التجزئة أو خدمات الطعام.', 'photo-1536304993881-ff6e9eefa2a6'],
            ['rice', 'parboiled-long-grain-rice', 'Parboiled Long Grain Rice', 'أرز طويل الحبة مسلوق جزئياً', 'A parboiled rice option with specifications and pack sizes to be confirmed.', 'خيار أرز مسلوق جزئياً مع مواصفات وأحجام تعبئة يتم تأكيدها.', 'photo-1592997571659-0b21ff64313b'],
            ['wheat', 'hard-wheat-grain', 'Hard Wheat Grain', 'قمح صلب', 'A wheat sourcing enquiry for milling and food-industry buyers.', 'استفسار توريد قمح لمطاحن ومشتري الصناعات الغذائية.', 'photo-1574323347407-f5e1ad6d020b'],
            ['wheat', 'soft-wheat-grain', 'Soft Wheat Grain', 'قمح طري', 'A soft wheat listing for buyer review; grade and crop details confirmed on enquiry.', 'منتج قمح طري للمراجعة؛ تؤكد الدرجة وتفاصيل المحصول عند الاستفسار.', 'photo-1500382017468-9049fed747ef'],
            ['wheat', 'durum-wheat', 'Durum Wheat', 'قمح الديورم', 'A durum wheat option for pasta and semolina supply discussions.', 'خيار قمح ديورم لمناقشة توريد المعكرونة والسميد.', 'photo-1464226184884-fa280b87c399'],
            ['grains', 'chickpeas', 'Chickpeas', 'حمص', 'Whole chickpeas for food-service, wholesale and packing enquiries.', 'حمص كامل لاستفسارات خدمات الطعام والجملة والتعبئة.', 'photo-1515543904379-3d757afe72e4'],
            ['grains', 'red-lentils', 'Red Lentils', 'عدس أحمر', 'Red lentils offered for buyer-led grade, origin and packaging discussions.', 'عدس أحمر لمناقشة الدرجة والمنشأ والتعبئة حسب متطلبات المشتري.', 'photo-1518977676601-b53f82aba655'],
            ['grains', 'yellow-corn', 'Yellow Corn', 'ذرة صفراء', 'A yellow corn sourcing option with quality parameters confirmed per enquiry.', 'خيار توريد ذرة صفراء مع تأكيد معايير الجودة حسب الاستفسار.', 'photo-1551754655-cd27e38d2076'],
            ['dry-fruits', 'medjool-dates', 'Medjool Dates', 'تمر مجدول', 'A premium date variety for retail, gifting and food-service enquiries.', 'صنف تمر مميز لاستفسارات التجزئة والهدايا وخدمات الطعام.', 'photo-1606313564200-e75d5e30476c'],
            ['dry-fruits', 'dried-figs', 'Dried Figs', 'تين مجفف', 'A dried fig selection for snack, retail and ingredient buyers.', 'تشكيلة تين مجفف لمشتري الوجبات الخفيفة والتجزئة والمكونات.', 'photo-1596591606975-97ee5cef3a1e'],
            ['dry-fruits', 'dried-apricots', 'Dried Apricots', 'مشمش مجفف', 'Dried apricots for buyer review, with pack formats discussed on enquiry.', 'مشمش مجفف للمراجعة مع مناقشة أشكال التعبئة عند الاستفسار.', 'photo-1610832958506-aa56368176cf'],
            ['dry-fruits', 'golden-raisins', 'Golden Raisins', 'زبيب ذهبي', 'Golden raisins for bakery, food-service and retail range discussions.', 'زبيب ذهبي لمناقشة منتجات المخابز وخدمات الطعام والتجزئة.', 'photo-1599599810694-b5ac4ddf2f46'],
            ['dry-fruits', 'raw-almonds', 'Raw Almonds', 'لوز نيء', 'Raw almonds for ingredient, wholesale and retail packaging enquiries.', 'لوز نيء لاستفسارات المكونات والجملة وعبوات التجزئة.', 'photo-1508061253366-f7da158b6d46'],
            ['dry-fruits', 'whole-cashews', 'Whole Cashews', 'كاجو كامل', 'Whole cashews presented for buyer-led grade and packing discussions.', 'كاجو كامل لمناقشة الدرجة والتعبئة حسب متطلبات المشتري.', 'photo-1563292769-4e05b124f140'],
            ['dry-fruits', 'pistachio-kernels', 'Pistachio Kernels', 'لب الفستق', 'Pistachio kernels for confectionery, ingredient and retail buyers.', 'لب فستق لمشتري الحلويات والمكونات والتجزئة.', 'photo-1528825871115-3581a5387919'],
            ['dry-fruits', 'walnut-halves', 'Walnut Halves', 'أنصاف الجوز', 'Walnut halves for bakery, ingredient and wholesale enquiries.', 'أنصاف الجوز لاستفسارات المخابز والمكونات والجملة.', 'photo-1563412885-139e4045ebb5'],
            ['dry-fruits', 'dried-prunes', 'Dried Prunes', 'برقوق مجفف', 'Dried prunes for snack, retail and food-service range discussions.', 'برقوق مجفف لمناقشة منتجات الوجبات الخفيفة والتجزئة وخدمات الطعام.', 'photo-1490474418585-ba9bad8fd0ea'],
            ['dry-fruits', 'dried-cranberries', 'Dried Cranberries', 'توت بري مجفف', 'Dried cranberries for bakery, snack and ingredient buyers.', 'توت بري مجفف لمشتري المخابز والوجبات الخفيفة والمكونات.', 'photo-1490750967868-88aa4486c946'],
            ['leather', 'classic-leather-jacket', 'Classic Leather Jacket', 'سترة جلدية كلاسيكية', 'A leather outerwear reference for style, material and private-label enquiries.', 'مرجع لملابس خارجية جلدية لاستفسارات التصميم والخامة والعلامة الخاصة.', 'photo-1551028719-00167b16eac5'],
            ['leather', 'leather-biker-jacket', 'Leather Biker Jacket', 'سترة دراجات جلدية', 'A contemporary leather jacket reference; material and construction to be confirmed.', 'مرجع سترة جلدية عصرية؛ يتم تأكيد الخامة والتصنيع.', 'photo-1529139574466-a303027c1d8b'],
            ['leather', 'leather-work-gloves', 'Leather Work Gloves', 'قفازات عمل جلدية', 'A leather accessories listing for workwear and uniform programme discussions.', 'منتج إكسسوارات جلدية لمناقشة برامج ملابس العمل والزي الموحد.', 'photo-1551488831-00ddcb6c6bd3'],
            ['leather', 'leather-crossbody-bag', 'Leather Crossbody Bag', 'حقيبة جلدية بحزام كتف', 'A leather goods reference for design, colour and own-label enquiries.', 'مرجع للمنتجات الجلدية لاستفسارات التصميم واللون والعلامة الخاصة.', 'photo-1548036328-c9fa89d128fa'],
            ['textile-garments', 'cotton-crewneck-t-shirt', 'Cotton Crewneck T-shirt', 'قميص قطني بياقة دائرية', 'A core apparel style for fabric, sizing and private-label discussions.', 'تصميم أساسي للملابس لمناقشة القماش والمقاسات والعلامة الخاصة.', 'photo-1521572163474-6864f9cf17ab'],
            ['textile-garments', 'everyday-cotton-hoodie', 'Everyday Cotton Hoodie', 'هودي قطني يومي', 'A casualwear reference for custom colour, fabric and branding enquiries.', 'مرجع للملابس الكاجوال لاستفسارات الألوان والأقمشة والعلامة التجارية.', 'photo-1556821840-3a63f95609a7'],
            ['textile-garments', 'denim-overshirt', 'Denim Overshirt', 'قميص جاكيت جينز', 'A denim garment reference for cut, wash and collection enquiries.', 'مرجع ملابس جينز لاستفسارات القصة والغسيل والتشكيلة.', 'photo-1543076447-215ad9ba6923'],
            ['textile-garments', 'workwear-uniform-set', 'Workwear Uniform Set', 'طقم زي موحد للعمل', 'A workwear reference for team uniforms and custom programme enquiries.', 'مرجع ملابس عمل لاستفسارات الزي الموحد للفرق والبرامج المخصصة.', 'photo-1515886657613-9f3515b0c78f'],
        ];

        $demoNote = 'Illustrative demo listing. Origin, specifications, availability, pricing, packaging and order quantities are confirmed with the buyer before any offer.';
        $demoNoteArabic = 'منتج تجريبي توضيحي. يتم تأكيد المنشأ والمواصفات والتوفر والأسعار والتعبئة والكميات مع المشتري قبل تقديم أي عرض.';
        $divisionIds = Division::query()->pluck('id', 'slug');
        $featuredByDivision = [];

        foreach ($demoProducts as $index => [$divisionSlug, $slug, $englishName, $arabicName, $englishSummary, $arabicSummary, $imageId]) {
            $product = Product::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'division_id' => $divisionIds[$divisionSlug],
                    'name' => ['en' => $englishName, 'ar' => $arabicName],
                    'short_description' => ['en' => $englishSummary, 'ar' => $arabicSummary],
                    'description' => [
                        'en' => $englishSummary.' '.$demoNote,
                        'ar' => $arabicSummary.' '.$demoNoteArabic,
                    ],
                    'origin' => null,
                    'moq_value' => null,
                    'moq_unit' => null,
                    'lead_time_days' => null,
                    'is_featured' => ! isset($featuredByDivision[$divisionSlug]),
                    'is_active' => true,
                    'sort_order' => $index,
                ],
            );
            $featuredByDivision[$divisionSlug] = true;

            ProductImage::query()->firstOrCreate(
                ['product_id' => $product->id, 'sort_order' => 0],
                [
                    'path' => 'https://images.unsplash.com/'.$imageId.'?auto=format&fit=crop&w=1000&q=85',
                    'alt' => ['en' => $englishName, 'ar' => $arabicName],
                    'is_primary' => true,
                ],
            );
        }

        $packages = [
            [
                'sample',
                'Sample',
                'عينة',
                'Discuss a sample to evaluate a product before deciding on a larger order.',
                'For importers and purchasing teams who want to assess product suitability.',
                'للمستوردين وفرق الشراء الراغبة في تقييم ملاءمة المنتج.',
            ],
            [
                'retail-pack',
                'Retail Pack',
                'عبوات التجزئة',
                'Discuss retail-oriented pack sizes, presentation and labelling, subject to confirmation.',
                'For retailers and brands planning consumer-facing product ranges.',
                'للمتاجر والعلامات التجارية التي تخطط لمنتجات موجهة للمستهلكين.',
            ],
            [
                'wholesale',
                'Wholesale',
                'الجملة',
                'Discuss commercial order quantities and product specifications for resale.',
                'For wholesalers, distributors and regional importers.',
                'لتجار الجملة والموزعين والمستوردين الإقليميين.',
            ],
            [
                'bulk-container',
                'Bulk / Container',
                'سائب / حاوية',
                'Discuss bulk handling or container shipment requirements for your enquiry.',
                'For buyers exploring larger-volume supply.',
                'للمشترين الذين يبحثون في خيارات التوريد بكميات أكبر.',
            ],
            [
                'private-label-oem',
                'Private Label / OEM',
                'علامة خاصة / تصنيع',
                'Discuss buyer-led branding, packaging and product specifications.',
                'For brands exploring own-label or custom product programmes.',
                'للعلامات التجارية التي تدرس برامج العلامة الخاصة أو المنتجات المخصصة.',
            ],
            [
                'long-term-contract',
                'Long-term Contract',
                'عقد طويل الأجل',
                'Discuss repeat orders, forecast requirements and a possible supply schedule.',
                'For buyers planning recurring demand over an agreed period.',
                'للمشترين الذين يخططون لاحتياجات متكررة خلال فترة متفق عليها.',
            ],
        ];

        foreach ($packages as $index => [$slug, $english, $arabic, $description, $audience, $audienceArabic]) {
            SupplyPackage::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => ['en' => $english, 'ar' => $arabic],
                    'audience' => ['en' => $audience, 'ar' => $audienceArabic],
                    'description' => ['en' => $description, 'ar' => 'ناقش معنا التفاصيل والمواصفات المتعلقة بهذا الخيار.'],
                    'min_qty_note' => ['en' => 'Minimum quantities are confirmed per product.', 'ar' => 'يتم تأكيد الحد الأدنى حسب المنتج.'],
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
        }

        Setting::query()->firstOrCreate(
            ['key' => 'certifications_visible'],
            ['value' => ['enabled' => false], 'group' => 'content'],
        );
    }
}
