<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Certification;
use App\Models\Division;
use App\Models\Product;
use App\Models\Setting;
use App\Models\SupplyPackage;
use Illuminate\Contracts\View\View;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'divisions' => Division::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'products' => Product::query()
                ->where('is_active', true)
                ->where('is_featured', true)
                ->with(['division', 'images'])
                ->orderBy('sort_order')
                ->limit(6)
                ->get(),
            'packages' => SupplyPackage::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function quality(): View
    {
        return view('pages.quality');
    }

    public function markets(): View
    {
        return view('pages.markets');
    }

    public function products(Request $request): View
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['division', 'images'])
            ->orderBy('sort_order');
        $search = trim((string) $request->query('q'));

        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('name', 'like', '%'.$search.'%')
                    ->orWhere('short_description', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('division')) {
            $divisionId = Division::query()
                ->where('slug', $request->string('division'))
                ->value('id');
            $query->where('division_id', $divisionId);
        }

        return view('pages.products', [
            'products' => $query->paginate(12)->withQueryString(),
            'divisions' => Division::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'search' => $search,
        ]);
    }

    public function division(string $locale, Division $division): View
    {
        app()->setLocale($locale);
        abort_unless($division->is_active, 404);

        return view('pages.division', [
            'division' => $division,
            'products' => $division->products()
                ->where('is_active', true)
                ->with(['division', 'images'])
                ->orderBy('sort_order')
                ->paginate(12),
        ]);
    }

    public function product(string $locale, Product $product): View
    {
        app()->setLocale($locale);
        abort_unless($product->is_active, 404);
        $product->load(['division', 'images', 'specs', 'packagingOptions', 'supplyPackages']);

        return view('pages.product', [
            'product' => $product,
            'relatedProducts' => Product::query()
                ->where('is_active', true)
                ->where('division_id', $product->division_id)
                ->where('id', '!=', $product->id)
                ->with(['division', 'images'])
                ->limit(3)
                ->get(),
        ]);
    }

    public function packages(): View
    {
        return view('pages.packages', [
            'packages' => SupplyPackage::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function catalog(): View
    {
        return view('pages.catalog', [
            'catalogs' => Catalog::query()->where('is_active', true)->with('division')->latest()->get(),
            'divisions' => Division::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function certifications(string $locale): View
    {
        app()->setLocale($locale);
        $visible = (bool) data_get(Setting::query()->where('key', 'certifications_visible')->first()?->value, 'enabled', false);
        abort_unless($visible, 404);

        return view('pages.certifications', [
            'certifications' => Certification::query()->where('is_visible', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function downloadCatalog(Request $request, string $locale, Catalog $catalog): Response
    {
        app()->setLocale($locale);
        abort_unless($catalog->is_active, 404);

        $validated = $request->validate([
            'email' => [$catalog->requires_email ? 'required' : 'nullable', 'email', 'max:255'],
        ]);

        /** @var FilesystemAdapter $publicDisk */
        $publicDisk = Storage::disk('public');
        abort_unless($publicDisk->exists($catalog->file_path), 404);

        $catalog->downloads()->create([
            'email' => $validated['email'] ?? null,
            'ip_address' => $request->ip(),
        ]);

        return $publicDisk->download($catalog->file_path);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function legal(string $locale, string $page): View
    {
        app()->setLocale($locale);
        abort_unless(\in_array($page, ['privacy', 'terms'], true), 404);

        return view('pages.legal', ['page' => $page]);
    }

    public function sitemap(): Response
    {
        $locales = ['en', 'ar'];
        $staticPaths = [
            '',
            '/about',
            '/products',
            '/supply-packages',
            '/quality',
            '/export-markets',
            '/catalog',
            '/contact',
            '/request-a-quote',
        ];
        $urlGroups = collect($staticPaths)
            ->map(fn (string $path): array => collect($locales)
                ->mapWithKeys(fn (string $locale): array => [$locale => url('/'.$locale.$path)])
                ->all());

        $urlGroups = $urlGroups->concat(
            Division::query()
                ->where('is_active', true)
                ->get()
                ->map(fn (Division $division): array => collect($locales)
                    ->mapWithKeys(fn (string $locale): array => [
                        $locale => route('divisions.show', ['locale' => $locale, 'division' => $division->slug]),
                    ])
                    ->all())
        )->concat(
            Product::query()
                ->where('is_active', true)
                ->get()
                ->map(fn (Product $product): array => collect($locales)
                    ->mapWithKeys(fn (string $locale): array => [
                        $locale => route('products.show', ['locale' => $locale, 'product' => $product->slug]),
                    ])
                    ->all())
        );

        $xml = new \XMLWriter;
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElementNS(null, 'urlset', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');

        foreach ($urlGroups as $localizedUrls) {
            foreach ($localizedUrls as $localizedUrl) {
                $xml->startElement('url');
                $xml->writeElement('loc', $localizedUrl);

                foreach ($localizedUrls as $alternateLocale => $alternateUrl) {
                    $xml->startElementNS('xhtml', 'link', 'http://www.w3.org/1999/xhtml');
                    $xml->writeAttribute('rel', 'alternate');
                    $xml->writeAttribute('hreflang', $alternateLocale);
                    $xml->writeAttribute('href', $alternateUrl);
                    $xml->endElement();
                }

                $xml->startElementNS('xhtml', 'link', 'http://www.w3.org/1999/xhtml');
                $xml->writeAttribute('rel', 'alternate');
                $xml->writeAttribute('hreflang', 'x-default');
                $xml->writeAttribute('href', $localizedUrls['en']);
                $xml->endElement();
                $xml->endElement();
            }
        }

        $xml->endElement();
        $xml->endDocument();

        return response($xml->outputMemory(), 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: ".route('sitemap')."\n";

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
