<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    use UploadsFiles;

    public function index(Request $request)
    {
        $teachers = Teacher::withCount('subjects')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('designation', 'like', $term)
                        ->orWhere('qualification', 'like', $term);
                });
            })
            ->when($request->filled('department'), fn ($q) => $q->where('department', $request->string('department')))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('backend.teachers.index', [
            'teachers' => $teachers,
            'departments' => Teacher::whereNotNull('department')->distinct()->orderBy('department')->pluck('department'),
        ]);
    }

    public function create()
    {
        return view('backend.teachers.form', ['teacher' => new Teacher(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->uploadImage($request->file('photo'), 'teachers');

        Teacher::create($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher added successfully.');
    }

    public function show(Teacher $teacher)
    {
        return view('backend.teachers.show', [
            'teacher' => $teacher->load('subjects.schoolClass'),
        ]);
    }

    public function edit(Teacher $teacher)
    {
        return view('backend.teachers.form', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $this->validated($request, $teacher->id);

        if ($photo = $this->uploadImage($request->file('photo'), 'teachers')) {
            $this->deleteImage($teacher->photo);
            $data['photo'] = $photo;
        }

        $teacher->update($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $this->deleteImage($teacher->photo);
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'name_bn' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:150', 'unique:teachers,email' . ($ignoreId ? ',' . $ignoreId : '')],
            'phone' => ['nullable', 'string', 'max:25'],
            'designation' => ['nullable', 'string', 'max:100'],
            'designation_bn' => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'department_bn' => ['nullable', 'string', 'max:100'],
            'qualification' => ['nullable', 'string', 'max:150'],
            'qualification_bn' => ['nullable', 'string', 'max:150'],
            'experience' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1500'],
            'bio_bn' => ['nullable', 'string', 'max:1500'],
            'photo' => $this->imageRules(),
            'join_date' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
