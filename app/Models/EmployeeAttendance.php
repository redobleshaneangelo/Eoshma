<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'time_in',
        'time_out',
        'payroll_start',
        'payroll_end',
        'payroll_type',
        'payroll_frequency'
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'payroll_start' => 'date',
            'payroll_end' => 'date'
        ];
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeTest::class, 'employee_id');
    }
}
