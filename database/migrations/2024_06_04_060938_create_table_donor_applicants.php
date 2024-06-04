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
        Schema::create('donor_applicants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->integer('user_id');
            $table->integer('status_id');
            $table->string('name');
            $table->string('nik');
            $table->date('dob');
            $table->enum('gender', ['M', 'F'])->default('M');
            $table->char('phone_number', 16);
            $table->text('address');
            $table->char('blood_type', 2);
            $table->integer('body_mass');
            $table->integer('hemoglobin_level');
            $table->string('blood_pressure');
            $table->text('medical_conditions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donor_applicants');
    }
};
