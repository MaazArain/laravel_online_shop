<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products' , function(Blueprint $table){
            $table->longText('short_description')->after('description');
            $table->longText('shipping_returns')->after('short_description');
            $table->longText('related_products')->after('shipping_returns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products' , function(Blueprint $table){
           $table->dropColumn('short_description');
           $table->dropColumn('shipping_returns');
           $table->dropColumn('related_products');
        });
    }
};
