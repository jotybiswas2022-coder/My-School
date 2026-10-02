<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        return view('backend.classes.index', [
            'classes' => SchoolClass::ordered()->with(['sections', 'subjects'])->withCount('students')->get(),
        ]);
    }

    public function create()
    {
        return view('backend.classes.form', [
            'schoolClass' => new SchoolClass(),
            'subjects' => Subject::orderBy('name')->get(),
            'selectedSubjects' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'name_bn' => ['nullable', 'string', 'max:80'],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:500'],
            'description_bn' => ['nullable', 'string', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ]);

        $class = SchoolClass::create([
            'name' => $data['name'],
            'name_bn' => $data['name_bn'] ?? null,
            'code' => $data['code'] ?? null,
            'description' => $data['description'] ?? null,
            'description_bn' => $data['description_bn'] ?? null,
            'order' => $data['order'] ?? 0,
        ]);

        $class->subjects()->sync($data['subjects'] ?? []);

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
    }

    public function edit(SchoolClass $class)
    {
        return view('backend.classes.form', [
            'schoolClass' => $class,
            'subjects' => Subject::orderBy('name')->get(),
            'selectedSubjects' => $class->subjects()->pluck('subjects.id')->toArray(),
        ]);
    }

    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'name_bn' => ['nullable', 'string', 'max:80'],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:500'],
            'description_bn' => ['nullable', 'string', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ]);

        $class->update([
            'name' => $data['name'],
            'name_bn' => $data['name_bn'] ?? null,
            'code' => $data['code'] ?? null,
            'description' => $data['description'] ?? null,
            'description_bn' => $data['description_bn'] ?? null,
            'order' => $data['order'] ?? 0,
        ]);

        $class->subjects()->sync($data['subjects'] ?? []);

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }

    public function storeSection(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        $class->sections()->create([
            'name' => $data['name'],
            'capacity' => $data['capacity'] ?? 40,
        ]);

        return back()->with('success', 'Section added successfully.');
    }

    public function destroySection(Section $section)
    {
        $section->delete();

        return back()->with('success', 'Section removed successfully.');
    }
}
