<?php

namespace App\Http\Controllers;

use App\Models\Academician;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicianController extends Controller
{
    public function index()
    {
        $academicians = Academician::all();

        return view('academicians.index', compact('academicians'));
    }

    public function create()
    {
        return view('academicians.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        Academician::create($validated);

        return redirect()->route('academicians.index')->with('success', 'Academician added successfully.');
    }

    public function edit(Academician $academician)
    {
        return view('academicians.edit', compact('academician'));
    }

    public function update(Request $request, Academician $academician)
    {
        $validated = $request->validate($this->rules($academician));

        $academician->update($validated);

        return redirect()->route('academicians.index')->with('success', 'Academician updated successfully.');
    }

    public function destroy(Academician $academician)
    {
        $academician->delete();

        return redirect()->route('academicians.index')->with('success', 'Academician deleted successfully.');
    }

    private function rules(?Academician $academician = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'staff_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('academicians', 'staff_number')->ignore($academician),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('academicians', 'email')->ignore($academician),
            ],
            'college' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'position' => [
                'required',
                Rule::in(['Professor', 'Assoc Prof', 'Senior Lecturer', 'Lecturer']),
            ],
        ];
    }
}
