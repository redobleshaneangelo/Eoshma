<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAttendance;
use App\Models\EmployeeTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function records(Request $request)
    {
        $date = $request->query('date');
        $dateValue = $date ? Carbon::parse($date) : Carbon::today();
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $employee = $this->getEmployeeForUser($user);


        $rows = EmployeeAttendance::query()
            ->where('employee_id', $employee->id)
            ->when($date, function ($query) use ($dateValue) {
                $query->whereDate('attendance_date', $dateValue->toDateString());
            })
            ->orderByDesc('attendance_date')
            ->get()
                ->map(function ($row) {
                $dateValue = Carbon::parse($row->attendance_date);
                    $status = 'time-in';
                    if (!$row->time_in && !$row->time_out) {
                        $status = 'absent';
                    } elseif ($row->time_out) {
                        $status = 'clocked-out';
                    }
                return [
                    'date' => $dateValue->toDateString(),
                    'dayOfWeek' => $dateValue->format('l'),
                    'timeIn' => $row->time_in,
                    'timeOut' => $row->time_out,
                        'status' => $status
                ];
            });

        return response()->json([
            'data' => $rows,
            'meta' => [
                'date' => $dateValue->toDateString(),
                'dayOfWeek' => $dateValue->format('l'),
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'position' => $employee->position
                ]
            ]
        ]);
    }

    public function updateRecord(Request $request, string $date)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $employee = $this->getEmployeeForUser($user);
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

    private function getEmployeeForUser($user): EmployeeTest
    {
        return EmployeeTest::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->full_name ?? trim(($user->last_name ?? '') . ', ' . ($user->first_name ?? '')),
                'position' => 'Employee',
                'rate' => 80
            ]
        );
    }
}
