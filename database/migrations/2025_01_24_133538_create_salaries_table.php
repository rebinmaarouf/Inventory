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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // Foreign key for employee
            $table->integer('amount')->nullable();
            $table->string('salary_date')->nullable();
            $table->string('salary_month')->nullable();
            $table->string('salary_year')->nullable();
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade'); // Optional: Delete salaries if employee is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
