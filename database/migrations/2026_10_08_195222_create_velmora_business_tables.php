<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('name');
            $table->json('summary')->nullable();
            $table->json('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->timestamps();
        });

        Schema::create('supply_packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('name');
            $table->json('audience')->nullable();
            $table->json('description')->nullable();
            $table->json('min_qty_note')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('packaging_options', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('kind', 32)->default('custom');
            $table->decimal('size_value', 12, 3)->nullable();
            $table->string('size_unit', 32)->nullable();
            $table->json('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->restrictOnDelete();
            $table->string('slug')->unique();
            $table->json('name');
            $table->json('short_description')->nullable();
            $table->json('description')->nullable();
            $table->string('origin')->nullable();
            $table->decimal('moq_value', 12, 3)->nullable();
            $table->string('moq_unit', 32)->nullable();
            $table->unsignedSmallInteger('lead_time_days')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['division_id', 'is_active', 'is_featured']);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->json('alt')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_specs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->json('spec_key');
            $table->json('spec_value');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_packaging', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('packaging_option_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'packaging_option_id']);
        });

        Schema::create('product_supply_package', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supply_package_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'supply_package_id']);
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 50)->nullable();
            $table->string('whatsapp', 50)->nullable();
            $table->string('company')->nullable();
            $table->string('designation')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->string('source')->nullable();
            $table->boolean('marketing_opt_in')->default(false);
            $table->timestamps();
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique()->nullable();
            $table->foreignId('contact_id')->constrained()->restrictOnDelete();
            $table->string('status', 24)->default('new')->index();
            $table->string('priority', 16)->default('normal');
            $table->string('destination_port')->nullable();
            $table->string('delivery_timeline')->nullable();
            $table->text('message')->nullable();
            $table->string('locale', 5)->default('en');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('quoted_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['assigned_to', 'created_at']);
        });

        Schema::create('inquiry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name_text')->nullable();
            $table->decimal('quantity', 14, 3)->nullable();
            $table->string('unit', 32)->nullable();
            $table->foreignId('supply_package_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('packaging_option_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('inquiry_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained()->cascadeOnDelete();
            $table->string('disk');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime', 120);
            $table->unsignedBigInteger('size');
            $table->timestamps();
        });

        Schema::create('inquiry_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 50)->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('status', 24)->default('new');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('contact_id')->constrained()->restrictOnDelete();
            $table->string('visitor_token');
            $table->string('status', 20)->default('waiting')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('inquiry_id')->nullable()->constrained()->nullOnDelete();
            $table->text('page_url')->nullable();
            $table->string('locale', 5)->default('en');
            $table->string('country_code', 2)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('rating_comment')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->unsignedInteger('unread_agent_count')->default(0);
            $table->unsignedInteger('unread_visitor_count')->default(0);
            $table->timestamps();
            $table->index(['assigned_to', 'last_message_at']);
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('chat_conversations')->cascadeOnDelete();
            $table->string('sender_type', 16);
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->text('body');
            $table->string('type', 16)->default('text');
            $table->boolean('is_internal')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->index(['conversation_id', 'id']);
        });

        Schema::create('chat_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('chat_messages')->cascadeOnDelete();
            $table->string('disk');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime', 120);
            $table->unsignedBigInteger('size');
            $table->timestamps();
        });

        Schema::create('catalogs', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->foreignId('division_id')->nullable()->constrained()->nullOnDelete();
            $table->string('file_path');
            $table->boolean('requires_email')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('catalog_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalog_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('scope', 16)->default('global');
            $table->unsignedBigInteger('scope_id')->nullable();
            $table->json('question');
            $table->json('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->string('group', 64)->default('general');
            $table->timestamps();
        });

        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('issuer')->nullable();
            $table->string('file_path')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('is_visible')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('catalog_downloads');
        Schema::dropIfExists('catalogs');
        Schema::dropIfExists('chat_attachments');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversations');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('inquiry_notes');
        Schema::dropIfExists('inquiry_attachments');
        Schema::dropIfExists('inquiry_items');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('product_supply_package');
        Schema::dropIfExists('product_packaging');
        Schema::dropIfExists('product_specs');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('packaging_options');
        Schema::dropIfExists('supply_packages');
        Schema::dropIfExists('divisions');
    }
};
