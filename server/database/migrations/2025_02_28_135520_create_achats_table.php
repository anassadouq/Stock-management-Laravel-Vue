<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facture_id')->nullable()->constrained('factures')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->string('qte');
            $table->string('pu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};