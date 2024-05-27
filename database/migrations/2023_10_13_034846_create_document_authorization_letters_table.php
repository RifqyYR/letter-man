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
        Schema::create('document_authorization_letters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number')->unique();
            $table->string('title');
            $table->string('contract_number');
            $table->string('payment_total');
            $table->string('created_by');
            $table->string('vendor_name');
            $table->string('bank_name');
            $table->string('account_number');
            $table->foreignUuid('vendor_id')->nullable()->references('id')->on('vendors');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_authorization_letters');
    }
};
