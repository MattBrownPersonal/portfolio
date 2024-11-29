<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModelRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_images', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_image_id')->change();
        });
        Schema::table('additional_images', function (Blueprint $table) {
            $table->foreign('custom_image_id')->references('id')->on('custom_images');
        });

        Schema::table('article_sites', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id')->change();
            $table->unsignedBigInteger('site_id')->change();
        });
        Schema::table('article_sites', function (Blueprint $table) {
            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('site_id')->references('id')->on('sites');
        });

        Schema::table('attribute_types', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
        });
        Schema::table('attribute_types', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_types_id')->change();
        });
        Schema::table('attributes', function (Blueprint $table) {
            $table->foreign('attribute_types_id')->references('id')->on('attribute_types');
        });

        Schema::table('custom_images', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
            $table->unsignedBigInteger('image_id')->change();
            $table->unsignedBigInteger('material_id')->change();
            $table->unsignedBigInteger('custom_image_id')->change();
        });
        Schema::table('custom_images', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('image_id')->references('id')->on('images');
            $table->foreign('material_id')->references('id')->on('materials');
        });

        Schema::table('custom_product_texts', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
        });
        Schema::table('custom_product_texts', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('custom_text_fields', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_image_id')->change();
            $table->unsignedBigInteger('custom_product_text_id')->change();
        });
        Schema::table('custom_text_fields', function (Blueprint $table) {
            $table->foreign('custom_image_id')->references('id')->on('custom_images');
            $table->foreign('custom_product_text_id')->references('id')->on('custom_product_texts');
        });

        Schema::table('custom_text_perspectives', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_image_id')->change();
        });
        Schema::table('custom_text_perspectives', function (Blueprint $table) {
            $table->foreign('custom_image_id')->references('id')->on('custom_images');
        });

        Schema::table('deceaseds', function (Blueprint $table) {
            $table->unsignedBigInteger('site_id')->change();
        });
        Schema::table('deceaseds', function (Blueprint $table) {
            $table->foreign('site_id')->references('id')->on('sites');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->unsignedBigInteger('site_id')->change();
        });
        Schema::table('faqs', function (Blueprint $table) {
            $table->foreign('site_id')->references('id')->on('sites');
        });

        Schema::table('font_product', function (Blueprint $table) {
            $table->unsignedBigInteger('font_id')->change();
            $table->unsignedBigInteger('product_id')->change();
        });
        Schema::table('font_product', function (Blueprint $table) {
            $table->foreign('font_id')->references('id')->on('fonts');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('image_product', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id')->change();
            $table->unsignedBigInteger('product_id')->change();
        });
        Schema::table('image_product', function (Blueprint $table) {
            $table->foreign('image_id')->references('id')->on('images');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('item_order_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('item_order_id')->change();
            $table->unsignedBigInteger('user_id')->change();
            $table->unsignedBigInteger('old_status_id')->change();
            $table->unsignedBigInteger('new_status_id')->change();
        });
        Schema::table('item_order_notes', function (Blueprint $table) {
            $table->foreign('item_order_id')->references('id')->on('item_orders');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('old_status_id')->references('id')->on('statuses');
            $table->foreign('new_status_id')->references('id')->on('statuses');
        });

        Schema::table('item_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->change();
            $table->unsignedBigInteger('status_id')->change();
        });
        Schema::table('item_orders', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('status_id')->references('id')->on('statuses');
        });

        Schema::table('lines', function (Blueprint $table) {
            $table->unsignedBigInteger('item_order_id')->change();
        });
        Schema::table('lines', function (Blueprint $table) {
            $table->foreign('item_order_id')->references('id')->on('item_orders');
        });

        Schema::table('link_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('deceased_id')->change();
        });
        Schema::table('link_logs', function (Blueprint $table) {
            $table->foreign('deceased_id')->references('id')->on('deceaseds');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
        });
        Schema::table('materials', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('memorialisation_sites', function (Blueprint $table) {
            $table->unsignedBigInteger('memorialisation_id')->change();
            $table->unsignedBigInteger('site_id')->change();
        });
        Schema::table('memorialisation_sites', function (Blueprint $table) {
            $table->foreign('memorialisation_id')->references('id')->on('memorialisations');
            $table->foreign('site_id')->references('id')->on('sites');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('deceased_id')->change();
            $table->unsignedBigInteger('site_id')->change();
            $table->unsignedBigInteger('status_id')->change();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('deceased_id')->references('id')->on('deceaseds');
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('status_id')->references('id')->on('statuses');
        });

        Schema::table('predefined_images', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
        });
        Schema::table('predefined_images', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('product_text_colours', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
        });
        Schema::table('product_text_colours', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->change();
            $table->unsignedBigInteger('site_id')->change();
            $table->unsignedBigInteger('memorialisation_id')->change();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('memorialisation_id')->references('id')->on('memorialisations');
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->change();
            $table->unsignedBigInteger('user_id')->change();
        });
        Schema::table('role_user', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->unsignedBigInteger('site_id')->change();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->foreign('site_id')->references('id')->on('sites');
        });

        Schema::table('site_styles', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id')->change();
            $table->unsignedBigInteger('site_id')->change();
        });
        Schema::table('site_styles', function (Blueprint $table) {
            $table->foreign('image_id')->references('id')->on('images');
            $table->foreign('site_id')->references('id')->on('sites');
        });

        Schema::table('site_supplier', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->change();
            $table->unsignedBigInteger('site_id')->change();
        });
        Schema::table('site_supplier', function (Blueprint $table) {
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });

        Schema::table('site_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->unsignedBigInteger('site_id')->change();
        });
        Schema::table('site_user', function (Blueprint $table) {
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
