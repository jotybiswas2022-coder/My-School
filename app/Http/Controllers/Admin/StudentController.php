<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    use UploadsFiles;

    public function index(Request $request)
    {
        $students = Student::with(['schoolClass', 'section'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('student_id', 'like', $term)
                        ->orWhere('guardian_name', 'like', $term);
                });
            })
            ->when($request->filled('class_id'), fn ($q) => $q->where('class_id', $request->integer('class_id')))
            ->when($request->filled('section_id'), fn ($q) => $q->where('section_id', $request->integer('section_id')))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('backend.students.index', [
            'students' => $students,
            'classes' => SchoolClass::ordered()->get(),
            'sections' => Section::with('schoolClass')->get(),
        ]);
    }

    public function create()
    {
        return view('backend.students.form', [
            'student' => new Student(['is_active' => true, 'admission_date' => now()]),
            'classes' => SchoolClass::ordered()->get(),
            'sections' => Section::with('schoolClass')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->uploadImage($request->file('photo'), 'students');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->string('password'));
        }

        Student::create($data);

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['schoolClass', 'section']);

        return view('backend.students.show', [
            'student' => $student,
            'summary' => $student->attendanceSummary(),
            'results' => $student->results()->with(['subject', 'exam'])->latest()->take(10)->get(),
        ]);
    }

    public function edit(Student $student)
    {
        return view('backend.students.form', [
            'student' => $student,
            'classes' => SchoolClass::ordered()->get(),
            'sections' => Section::with('schoolClass')->get(),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $this->validated($request, $student->id);

        if ($photo = $this->uploadImage($request->file('photo'), 'students')) {
            $this->deleteImage($student->photo);
            $data['photo'] = $photo;
        } elseif ($request->boolean('remove_photo') && $student->photo) {
            $this->deleteImage($student->photo);
            $data['photo'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->string('password'));
        }

        $student->update($data);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $this->deleteImage($student->photo);
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'student_id' => ['required', 'string', 'max:50', 'unique:students,student_id' . ($ignoreId ? ',' . $ignoreId : '')],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:150', 'unique:students,email' . ($ignoreId ? ',' . $ignoreId : '')],
            'password' => ['nullable', 'string', 'min:6'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'roll_number' => ['nullable', 'string', 'max:20'],
            'guardian_name' => ['nullable', 'string', 'max:120'],
            'guardian_phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:500'],
            'admission_date' => ['nullable', 'date'],
            'photo' => $this->imageRules(),
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
