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
        Schema::create('employers', function (Blueprint $table) {
        $table->id();
        $table->string('company_name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('industry');
        $table->text('description');
        $table->string('website')->nullable();
        $table->string('phone');
        $table->string('location');
        $table->string('contact_person');
        $table->string('company_pic')->nullable();
        $table->string('otp')->nullable();
        $table->timestamp('otp_expires_at')->nullable();
        $table->boolean('is_verified')->default(false);
        $table->string('reset_token')->nullable();
        $table->timestamp('reset_token_expires_at')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};
