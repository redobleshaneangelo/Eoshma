<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_run_eligibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained('payroll_runs')->cascadeOnDelete();
            $table->foreignId('employee_test_id')->constrained('employees_test')->cascadeOnDelete();
            $table->boolean('is_eligible')->default(false);
            $table->timestamps();

            $table->unique(['payroll_run_id', 'employee_test_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_run_eligibilities');
    }
};
