<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->integer('class_id') ?: SchoolClass::ordered()->value('id');
        $sectionId = $request->integer('section_id');
        $date = $request->date('date') ?: now();

        $students = collect();
        if ($classId) {
            $students = Student::where('class_id', $classId)
                ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            $existing = Attendance::whereDate('date', $date->toDateString())
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');

            $students->each(fn ($s) => $s->setAttribute('attendance', $existing->get($s->id)));
        }

        return view('backend.attendance.index', [
            'classes' => SchoolClass::ordered()->get(),
            'sections' => Section::when($classId, fn ($q) => $q->where('class_id', $classId))->get(),
            'students' => $students,
            'classId' => $classId,
            'sectionId' => $sectionId,
            'date' => $date,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'attendance' => ['required', 'array'],
            'attendance.*.status' => ['required', 'in:present,absent,late'],
            'attendance.*.remark' => ['nullable', 'string', 'max:150'],
        ]);

        foreach ($data['attendance'] as $studentId => $row) {
            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $data['date']],
                [
                    'class_id' => $data['class_id'],
                    'section_id' => $data['section_id'] ?? null,
                    'status' => $row['status'],
                    'remark' => $row['remark'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.attendance.index', [
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'] ?? null,
            'date' => $data['date'],
        ])->with('success', 'Attendance saved successfully.');
    }

    public function report(Request $request)
    {
        $classId = $request->integer('class_id');
        $sectionId = $request->integer('section_id');
        $from = $request->date('from') ?: now()->startOfMonth();
        $to = $request->date('to') ?: now();

        $query = Attendance::with('student')
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId));

        $totals = (clone $query)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $perStudent = (clone $query)
            ->selectRaw("student_id, sum(case when status = 'present' then 1 else 0 end) as present, sum(case when status = 'absent' then 1 else 0 end) as absent, sum(case when status = 'late' then 1 else 0 end) as late, count(*) as total")
            ->groupBy('student_id')
            ->with('student')
            ->get();

        return view('backend.attendance.report', [
            'classes' => SchoolClass::ordered()->get(),
            'sections' => Section::when($classId, fn ($q) => $q->where('class_id', $classId))->get(),
            'classId' => $classId,
            'sectionId' => $sectionId,
            'from' => $from,
            'to' => $to,
            'totals' => $totals,
            'perStudent' => $perStudent,
        ]);
    }
}
