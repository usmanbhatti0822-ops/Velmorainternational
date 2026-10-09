<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PublicSiteController;

Route::redirect('/', '/en');

Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->middleware('throttle:5,1')->name('admin.login.store');
Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])->middleware('auth')->name('admin.logout');

Route::prefix('chat')->name('chat.')->group(function () {
    Route::post('/start', [ChatController::class, 'start'])->middleware('throttle:chat-start')->name('start');
    Route::post('/offline', [ChatController::class, 'offline'])->middleware('throttle:chat-start')->name('offline');
    Route::get('/{uuid}/messages', [ChatController::class, 'index'])->middleware('throttle:chat-poll')->name('messages.index');
    Route::post('/{uuid}/messages', [ChatController::class, 'store'])->middleware('throttle:chat-message')->name('messages.store');
    Route::post('/{uuid}/attachments', [ChatController::class, 'attach'])->middleware('throttle:chat-message')->name('attachments.store');
    Route::get('/{uuid}/attachments/{attachment}', [ChatController::class, 'downloadAttachment'])->name('attachments.show');
    Route::post('/{uuid}/rating', [ChatController::class, 'rate'])->middleware('throttle:chat-start')->name('rating');
});

Route::prefix('{locale}')->where(['locale' => 'en|ar'])->middleware('locale')->group(function () {
    Route::get('/', [PublicSiteController::class, 'home'])->name('home');
    Route::get('/about', [PublicSiteController::class, 'about'])->name('about');
    Route::get('/quality', [PublicSiteController::class, 'quality'])->name('quality');
    Route::get('/export-markets', [PublicSiteController::class, 'markets'])->name('markets');
    Route::get('/certifications', [PublicSiteController::class, 'certifications'])->name('certifications');
    Route::get('/products', [PublicSiteController::class, 'products'])->name('products.index');
    Route::get('/divisions/{division:slug}', [PublicSiteController::class, 'division'])->name('divisions.show');
    Route::get('/products/{product:slug}', [PublicSiteController::class, 'product'])->name('products.show');
    Route::get('/supply-packages', [PublicSiteController::class, 'packages'])->name('packages');
    Route::get('/catalog', [PublicSiteController::class, 'catalog'])->name('catalog');
    Route::get('/catalog/{catalog}/download', [PublicSiteController::class, 'downloadCatalog'])->middleware('throttle:10,1')->name('catalog.download');
    Route::get('/request-a-quote', [InquiryController::class, 'create'])->name('inquiries.create');
    Route::post('/request-a-quote', [InquiryController::class, 'store'])->middleware('throttle:5,1')->name('inquiries.store');
    Route::get('/contact', [PublicSiteController::class, 'contact'])->name('contact');
    Route::post('/contact', [ContactMessageController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
    Route::get('/privacy-policy', [PublicSiteController::class, 'legal'])->defaults('page', 'privacy')->name('legal.privacy');
    Route::get('/terms', [PublicSiteController::class, 'legal'])->defaults('page', 'terms')->name('legal.terms');
});

Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/inquiries', [AdminController::class, 'inquiries'])->name('inquiries');
    Route::get('/inquiries/export', [AdminController::class, 'exportInquiries'])->name('inquiries.export');
    Route::patch('/inquiries/{inquiry}', [AdminController::class, 'updateInquiry'])->name('inquiries.update');
    Route::get('/contact-messages', [AdminController::class, 'contactMessages'])->name('contact-messages');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::patch('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/certifications', [AdminController::class, 'storeCertification'])->name('certifications.store');
    Route::patch('/certifications/{certification}', [AdminController::class, 'updateCertification'])->name('certifications.update');
    Route::patch('/certifications/{certification}/visibility', [AdminController::class, 'updateCertificationVisibility'])->name('certifications.visibility');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::patch('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::get('/content', [AdminController::class, 'content'])->name('content');
    Route::patch('/divisions/{division}', [AdminController::class, 'updateDivision'])->name('divisions.update');
    Route::patch('/supply-packages/{package}', [AdminController::class, 'updateSupplyPackage'])->name('supply-packages.update');
    Route::post('/packaging-options', [AdminController::class, 'storePackagingOption'])->name('packaging-options.store');
    Route::post('/catalogs', [AdminController::class, 'storeCatalog'])->name('catalogs.store');
    Route::patch('/catalogs/{catalog}', [AdminController::class, 'updateCatalog'])->name('catalogs.update');
    Route::get('/conversations', [AdminController::class, 'conversations'])->name('conversations');
    Route::get('/conversations/{conversation}', [AdminController::class, 'conversation'])->name('conversations.show');
    Route::post('/conversations/{conversation}/reply', [AdminController::class, 'reply'])->name('conversations.reply');
    Route::patch('/conversations/{conversation}/status', [AdminController::class, 'updateConversation'])->name('conversations.update');
});

Route::get('/sitemap.xml', [PublicSiteController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PublicSiteController::class, 'robots'])->name('robots');
