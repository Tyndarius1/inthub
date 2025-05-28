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
        Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->date('dob');
        $table->string('gender');
        $table->string('phone');
        $table->string('address');
        $table->string('school');
        $table->string('course');
        $table->string('year_level');
        $table->string('student_id_number');
        $table->string('student_pic')->nullable();
        $table->string('otp')->nullable();
        $table->timestamp('otp_expires_at')->nullable();
        $table->string('reset_token')->nullable();
        $table->boolean('is_verified')->default(false);
        $table->timestamp('reset_token_expires_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
