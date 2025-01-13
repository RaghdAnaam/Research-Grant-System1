<?php

namespace App\Http\Controllers;

use App\Models\Academician;
use Illuminate\Http\Request;

class AcademicianController extends Controller
{
    public function index() {
        $academicians = Academician::all();
        return view('academicians.index', compact('academicians'));
    }
    
    public function create() {
        return view('academicians.create');
    }
    
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'staff_number' => 'required|unique:academicians',
            'email' => 'required|email|unique:academicians',
            'college' => 'required',
            'department' => 'required',
            'position' => 'required',
        ]);
    
        Academician::create($request->all());
        return redirect()->route('academicians.index')->with('success', 'Academician added successfully.');
    }
    

    public function edit(Academician $academician)
    {
        return view('academicians.edit', compact('academician'));
    }

    public function update(Request $request, Academician $academician)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'staff_number' => 'required|string|max:255|unique:academicians,staff_number,' . $academician->id,
            'email' => 'required|email|unique:academicians,email,' . $academician->id,
            'college' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'position' => 'required|string',
        ]);

        $academician->update($request->all());

        return redirect()->route('academicians.index')->with('success', 'Academician updated successfully.');
    }

    public function destroy(Academician $academician)
    {
        $academician->delete();

        return redirect()->route('academicians.index')->with('success', 'Academician deleted successfully.');
    }
}
