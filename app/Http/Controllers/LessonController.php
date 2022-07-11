<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonStoreRequest;
use App\Http\Requests\LessonUpdateRequest;
use App\Http\Resources\LessonResource;
use App\Http\Resources\Lessons\ClassroomResource;
use App\Http\Resources\LessonsResource;
use App\Models\Classroom;
use App\Models\Lesson;
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
        $lessons = LessonsResource::collection(Lesson::with('classroom:id,name')->get());

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
            $classroom = new ClassroomResource($classroom);
        }

        return Inertia::render('Lessons/Create', compact('classroom'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LessonStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LessonStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $lesson = Lesson::create($request->validated());
            $lesson->classroom->classroomUsers()->update(['credit' => DB::raw("`credit` - 1")]);

            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha creado la lección correctamente.');

            return redirect()->route('lessons.index');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            
            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al crear la lección. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.'.$th);

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        $lesson = new LessonResource($lesson);

        return Inertia::render('Lessons/Edit', compact('lesson'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LessonUpdateRequest  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(LessonUpdateRequest $request, Lesson $lesson)
    {
        try {
            DB::beginTransaction();

            if ($lesson->classroom_id !== $request->classroom_id) {
                $lesson->classroom->classroomUsers()->update(['credit' => DB::raw("`credit` + 1")]);
                $lesson->update($request->validated());
                $lesson->refresh()->classroom->classroomUsers()->update(['credit' => DB::raw("`credit` - 1")]);
            } else {
                $lesson->update($request->validated());
            }

            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha modificado la lección correctamente.');

            return Redirect::back();
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
    public function destroy(Lesson $lesson)
    {
        try {
            DB::beginTransaction();

            $lesson->classroom->classroomUsers()->update(['credit' => DB::raw("`credit` + 1")]);
            $lesson->delete();

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function classrooms()
    {
        return ClassroomResource::collection(Classroom::has('classroomUsers')->with('classroomUsers.user')->get());
    }
}
