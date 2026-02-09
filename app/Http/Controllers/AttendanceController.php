<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAttendance;
use App\Models\EmployeeTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function records(Request $request)
    {
        $date = $request->query('date');
        $dateValue = $date ? Carbon::parse($date) : Carbon::today();

        $statusCase = "CASE WHEN ea.time_in IS NULL AND ea.time_out IS NULL THEN 'absent' WHEN ea.time_out IS NOT NULL THEN 'clocked-out' ELSE 'time-in' END";

        $rows = EmployeeTest::query()
            ->leftJoin('employee_attendances as ea', function ($join) use ($dateValue) {
                $join->on('employees.id', '=', 'ea.employee_id')
                    ->whereDate('ea.attendance_date', $dateValue->toDateString());
            })
            ->select(
                'employees.id',
                'employees.name',
                'employees.position',
                'ea.time_in',
                'ea.time_out',
                DB::raw("{$statusCase} as status")
            )
            ->orderBy('employees.name')
            ->get()
            ->map(function ($row) {
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'position' => $row->position,
                    'timeIn' => $row->time_in,
                    'timeOut' => $row->time_out,
                    'status' => $row->status
                ];
            });

        return response()->json([
            'data' => $rows,
            'meta' => [
                'date' => $dateValue->toDateString(),
                'dayOfWeek' => $dateValue->format('l')
            ]
        ]);
    }

    public function updateRecord(Request $request, string $date, EmployeeTest $employee)
    {
        $validated = $request->validate([
            'time_in' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'time_out' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/']
        ]);

        $timeIn = $validated['time_in'] ?? null;
        $timeOut = $validated['time_out'] ?? null;

        if ($timeIn === '') {
            $timeIn = null;
        }

        if ($timeOut === '') {
            $timeOut = null;
        }

        if ($timeIn && strlen($timeIn) === 5) {
            $timeIn .= ':00';
        }

        if ($timeOut && strlen($timeOut) === 5) {
            $timeOut .= ':00';
        }

        $lastAttendance = EmployeeAttendance::query()
            ->where('employee_id', $employee->id)
            ->orderByDesc('attendance_date')
            ->first();

        EmployeeAttendance::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'attendance_date' => $date
            ],
            [
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'payroll_start' => $lastAttendance?->payroll_start?->toDateString() ?? $date,
                'payroll_end' => $lastAttendance?->payroll_end?->toDateString() ?? $date,
                'payroll_type' => $lastAttendance?->payroll_type ?? 'hour',
                'payroll_frequency' => $lastAttendance?->payroll_frequency ?? 'Weekly'
            ]
        );

        return response()->json(['status' => 'ok']);
    }
}
