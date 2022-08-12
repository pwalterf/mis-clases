<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonStoreRequest;
use App\Http\Requests\LessonUpdateRequest;
use App\Http\Resources\Admin\ClassroomStudentsResource;
use App\Http\Resources\Admin\LessonEditResource;
use App\Http\Resources\Admin\LessonListResource;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Services\Admin\LessonService;
use App\Services\Admin\StudentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lessons = LessonListResource::collection(Lesson::orderByDesc('lesson_date')->with('classroom:id,name')->get());

        return Inertia::render('Lessons/Index', compact('lessons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function create(Classroom $classroom = null)
    {
        if ($classroom) {
            $classroom = $classroom->deleted_at ? null : new ClassroomStudentsResource($classroom);
        }

        return Inertia::render('Lessons/Create', compact('classroom'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LessonStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LessonStoreRequest $request, LessonService $lessonService, StudentService $studentService)
    {
        try {
            DB::beginTransaction();

            $lesson = $lessonService->createLesson($request->validated());

            foreach ($lesson->classroom->classroomUsers as $student) {
                $studentService->updateCredit($student, -1);
            }

            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha creado la lección correctamente.');

            return Redirect::route('lessons.index');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            
            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al crear la lección. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        $lesson = new LessonEditResource($lesson);

        return Inertia::render('Lessons/Edit', compact('lesson'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LessonUpdateRequest  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(LessonUpdateRequest $request, Lesson $lesson, LessonService $lessonService, StudentService $studentService)
    {
        try {
            DB::beginTransaction();

            $studentsCredit = $lessonService->classroomChanged($lesson->classroom_id, $request->classroom_id);
            $lessonService->updateLesson($lesson, $request->validated());
            $studentService->syncCredit($studentsCredit);
            
            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha modificado la lección correctamente.');

            return Redirect::route('lessons.edit', $lesson->id);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al modificar la lección. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson, LessonService $lessonService, StudentService $studentService)
    {
        try {
            DB::beginTransaction();

            foreach ($lesson->classroom->classroomUsers as $student) {
                $studentService->updateCredit($student, 1);
            }

            $lessonService->destroyLesson($lesson);

            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha eliminado la lección correctamente.');

            return Redirect::route('lessons.index');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al eliminar la lección. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }
}
