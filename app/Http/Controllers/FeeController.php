<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\FeePayment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FeeController extends Controller
{
    public function index(): View
    {
        $fees = Fee::with('student.user', 'payments')->latest()->paginate(15);
        return view('fees.index', compact('fees'));
    }

    public function create(): View
    {
        $students = Student::with('user')->get();
        return view('fees.create', compact('students'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid'
        ]);

        Fee::create($validated);
        return redirect()->route('admin.fees.index')->with('success', 'Fee record created.');
    }

    public function show(Fee $fee): View
    {
        $fee->load('student.user', 'payments');
        return view('fees.show', compact('fee'));
    }

    public function edit(Fee $fee): View
    {
        return view('fees.edit', compact('fee'));
    }

    public function update(Request $request, Fee $fee): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid'
        ]);

        $fee->update($validated);
        return redirect()->route('admin.fees.index')->with('success', 'Fee updated.');
    }

    public function destroy(Fee $fee): RedirectResponse
    {
        $fee->delete();
        return redirect()->route('admin.fees.index')->with('success', 'Fee record deleted.');
    }

    public function addPayment(Request $request, Fee $fee): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string'
        ]);

        FeePayment::create([
            'fee_id' => $fee->id,
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'paid_at' => now(),
        ]);

        // Auto-update fee status if fully paid
        $totalPaid = $fee->payments()->sum('amount') + $validated['amount'];
        if ($totalPaid >= $fee->amount) {
            $fee->update(['status' => 'paid']);
        }

        return back()->with('success', 'Payment recorded successfully.');
    }
}
