<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::with(['teacher', 'schoolClass'])
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->string('q') . '%')
                ->orWhere('code', 'like', '%' . $request->string('q') . '%'))
            ->when($request->filled('class_id'), fn ($q) => $q->where('class_id', $request->integer('class_id')))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('backend.subjects.index', [
            'subjects' => $subjects,
            'classes' => SchoolClass::ordered()->get(),
        ]);
    }

    public function create()
    {
        return view('backend.subjects.form', [
            'subject' => new Subject(),
            'classes' => SchoolClass::ordered()->get(),
            'teachers' => Teacher::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Subject::create($this->validated($request));

        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return view('backend.subjects.form', [
            'subject' => $subject,
            'classes' => SchoolClass::ordered()->get(),
            'teachers' => Teacher::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Subject $subject)
    {
        $subject->update($this->validated($request, $subject->id));

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'name_bn' => ['nullable', 'string', 'max:120'],
            'code' => ['required', 'string', 'max:30', 'unique:subjects,code' . ($ignoreId ? ',' . $ignoreId : '')],
            'description' => ['nullable', 'string', 'max:500'],
            'description_bn' => ['nullable', 'string', 'max:500'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);
    }
}
