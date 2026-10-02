<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\SchoolClass;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index()
    {
        return view('frontend.admission', [
            'classes' => SchoolClass::ordered()->pluck('name'),
            'admissionOpen' => (bool) (Setting::get('admission_open', '1') === '1' || Setting::get('admission_open') === '1'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_name' => ['required', 'string', 'min:3', 'max:120'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'applying_class' => ['required', 'string', 'max:60'],
            'previous_school' => ['nullable', 'string', 'max:150'],
            'guardian_name' => ['required', 'string', 'max:120'],
            'guardian_phone' => ['required', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['required', 'string', 'max:500'],
            'additional_info' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['application_id'] = Admission::generateApplicationId();
        $data['status'] = 'pending';

        $admission = Admission::create($data);

        return redirect()
            ->route('admission.status', ['application' => $admission->application_id])
            ->with('success', 'Your application has been submitted successfully. Please save your Application ID.')
            ->with('application_id', $admission->application_id);
    }

    public function statusForm(Request $request)
    {
        $application = null;

        if ($request->filled('application_id')) {
            $application = Admission::where('application_id', $request->string('application_id'))->first();
        }

        $id = $request->route('application') ?? $request->old('application_id');

        if ($id && ! $application) {
            $application = Admission::where('application_id', $id)->first();
        }

        return view('frontend.admission-status', [
            'application' => $application,
            'searched' => $request->filled('application_id') || (bool) $id,
            'query' => $request->string('application_id')->toString() ?: ($id ?? ''),
        ]);
    }
}
