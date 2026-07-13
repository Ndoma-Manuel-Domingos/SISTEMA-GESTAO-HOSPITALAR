<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // get distinct courses for select filter
        $courses = Student::select('course')->distinct()->orderBy('course')->pluck('course');
        return view('students.index', compact('courses'));
    }

    // AJAX endpoint that returns rendered table HTML + pagination
    public function list(Request $request)
    {
        $request->validate([
            'page' => 'nullable|integer',
            'name' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
        ]);

        $query = Student::query();

        if ($request->filled('name')) $query->searchName($request->name);
        if ($request->filled('course')) $query->where('course', $request->course);

        $students = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $table = view('students._table', compact('students'))->render();
        $pagination = view('students._pagination', compact('students'))->render();

        return response()->json(['table' => $table, 'pagination' => $pagination]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:50',
            'course' => 'required|string|max:255',
            'enrolled_at' => 'nullable|date',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $student = Student::create($validator->validated());
        return response()->json(['message' => 'Estudante criado com sucesso', 'student' => $student]);
    }

    public function show(Student $student)
    {
        return response()->json($student);
    }

    public function update(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => "required|email|unique:students,email,{$student->id}",
            'phone' => 'nullable|string|max:50',
            'course' => 'required|string|max:255',
            'enrolled_at' => 'nullable|date',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $student->update($validator->validated());
        return response()->json(['message' => 'Estudante atualizado com sucesso', 'student' => $student]);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['message' => 'Estudante removido com sucesso']);
    }
}
