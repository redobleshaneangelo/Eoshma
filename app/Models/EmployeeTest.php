<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTest extends Model
{
    use HasFactory;

    protected $table = 'employees_test';

    protected $fillable = [
        'name',
        'position',
        'rate'
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2'
        ];
    }

    public function attendances()
    {
        return $this->hasMany(EmployeeAttendance::class, 'employee_test_id');
    }
}
