<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $admissions = Admission::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('student_name', 'like', $term)
                        ->orWhere('application_id', 'like', $term)
                        ->orWhere('guardian_name', 'like', $term);
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backend.admissions.index', [
            'admissions' => $admissions,
            'statuses' => Admission::statuses(),
            'counts' => [
                'pending' => Admission::where('status', 'pending')->count(),
                'approved' => Admission::where('status', 'approved')->count(),
                'rejected' => Admission::where('status', 'rejected')->count(),
            ],
        ]);
    }

    public function show(Admission $admission)
    {
        return view('backend.admissions.show', compact('admission'));
    }

    public function updateStatus(Request $request, Admission $admission)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $admission->update($data);

        return back()->with('success', 'Application status updated to ' . ucfirst($data['status']) . '.');
    }

    public function destroy(Admission $admission)
    {
        $admission->delete();

        return redirect()->route('admin.admissions.index')->with('success', 'Application deleted successfully.');
    }
}
