<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MissingIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_sites', function (Blueprint $table) {
            $table->index('article_id');
            $table->index('site_id');
        });
        Schema::table('attributes', function (Blueprint $table) {
            $table->index('attribute_types_id');
        });
        Schema::table('custom_images', function (Blueprint $table) {
            $table->index('custom_image_id');
            $table->index('image_id');
            $table->index('material_id');
            $table->index('product_id');
        });
        Schema::table('custom_product_texts', function (Blueprint $table) {
            $table->index('product_id');
        });
        Schema::table('custom_text_fields', function (Blueprint $table) {
            $table->index('custom_image_id');
            $table->index('custom_product_text_id');
        });
        Schema::table('deceaseds', function (Blueprint $table) {
            $table->index('code');
            $table->index('site_id');
        });
        Schema::table('faqs', function (Blueprint $table) {
            $table->index('hidden');
            $table->index('site_id');
            $table->index(['site_id', 'hidden']);
        });
        Schema::table('item_order_notes', function (Blueprint $table) {
            $table->index('item_order_id');
            $table->index('user_id');
        });
        Schema::table('item_orders', function (Blueprint $table) {
            $table->index('order_id');
        });
        Schema::table('materials', function (Blueprint $table) {
            $table->index('product_id');
        });
        Schema::table('memorialisation_sites', function (Blueprint $table) {
            $table->index('memorialisation_id');
            $table->index('site_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->index('deceased_id');
            $table->index('order_number');
            $table->index('site_id');
        });
        Schema::table('predefined_images', function (Blueprint $table) {
            $table->index('product_id');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->index('memorialisation_id');
            $table->index('supplier_id');
            $table->index('saved');
        });
        Schema::table('product_text_colours', function (Blueprint $table) {
            $table->index('product_id');
        });
        Schema::table('sites', function (Blueprint $table) {
            $table->index('name');
            $table->index('has_orphaned_products');
            $table->index(['has_orphaned_products', 'name']);
        });
        Schema::table('site_styles', function (Blueprint $table) {
            $table->index('site_id');
            $table->index('image_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_sites', function (Blueprint $table) {
            $table->dropIndex(['article_id']);
            $table->dropIndex(['site_id']);
        });
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropIndex(['attribute_types_id']);
        });
        Schema::table('custom_images', function (Blueprint $table) {
            $table->dropIndex(['custom_image_id']);
            $table->dropIndex(['image_id']);
            $table->dropIndex(['material_id']);
            $table->dropIndex(['product_id']);
        });
        Schema::table('custom_product_texts', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });
        Schema::table('custom_text_fields', function (Blueprint $table) {
            $table->dropIndex(['custom_image_id']);
            $table->dropIndex(['custom_product_text_id']);
        });
        Schema::table('deceaseds', function (Blueprint $table) {
            $table->dropIndex(['code']);
            $table->dropIndex(['supplier_id']);
        });
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex(['huidden']);
            $table->dropIndex(['site_id']);
            $table->dropIndex(['site_id', 'hidden']);
        });
        Schema::table('item_order_notes', function (Blueprint $table) {
            $table->dropIndex(['item_order_id']);
            $table->dropIndex(['user_id']);
        });
        Schema::table('item_orders', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
        });
        Schema::table('materials', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });
        Schema::table('memorialisation_sites', function (Blueprint $table) {
            $table->dropIndex(['memorialisation_id']);
            $table->dropIndex(['site_id']);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['deceased_id']);
            $table->dropIndex(['order_number']);
            $table->dropIndex(['site_id']);
        });
        Schema::table('predefined_images', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['memorialisation_id']);
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['saved']);
        });
        Schema::table('product_text_colours', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });
        Schema::table('sites', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['has_orphaned_products']);
            $table->dropIndex(['has_orphaned_products', 'name']);
        });
        Schema::table('site_styles', function (Blueprint $table) {
            $table->dropIndex(['site_id']);
            $table->dropIndex(['image_id']);
        });
    }
}
