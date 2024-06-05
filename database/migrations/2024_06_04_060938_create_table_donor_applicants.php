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
            $table->char('nik', 16);
            $table->integer('user_id');
            $table->integer('schedule_id');
            $table->integer('status_id');
            $table->string('name');
            $table->date('dob');
            $table->enum('gender', ['M', 'F'])->default('M');
            $table->char('phone_number', 16);
            $table->text('address');
            $table->char('blood_type', 3);
            $table->integer('body_mass');
            $table->float('hemoglobin_level');
            $table->string('blood_pressure');
            $table->text('medical_conditions')->nullable();
            $table->timestamps();
            $table->softDeletes();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            // $table->foreignId('status_id')->constrained('donor_statuses')->onDelete('cascade');
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
