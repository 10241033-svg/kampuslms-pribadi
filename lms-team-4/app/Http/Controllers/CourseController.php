<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('lecturer')->get();

        return view('courses.index', [
            'title' => 'Daftar Mata Kuliah',
            'courses' => $courses,
        ]);
    }

    public function show(Course $course)
    {
        $course->load('lecturer');

        return view('courses.show', [
            'title' => 'Detail Mata Kuliah',
            'course' => $course,
        ]);
    }
}