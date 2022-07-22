<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Http\Requests\ClassroomStoreRequest;
use App\Http\Requests\ClassroomUpdateRequest;
use App\Http\Resources\Admin\ClassroomEditResource;
use App\Http\Resources\Admin\LessonBasicResource;
use App\Http\Resources\Admin\ClassroomListResource;
use App\Http\Resources\Admin\ClassroomStudentsResource;
use App\Http\Resources\Admin\StudentResource;
use App\Services\Admin\ClassroomService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classrooms = ClassroomListResource::collection(Classroom::allWithLastSubscription());
        
        return Inertia::render('Classrooms/Index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Classrooms/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ClassroomStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassroomStoreRequest $request, ClassroomService $classroomService)
    {
        try {
            DB::beginTransaction();

            $classroom = $classroomService->createClassroom($request->safe()->only(['name', 'description']));
            $classroomService->createSubscription($classroom, $request->safe()->only(['price_hr']));
            $classroomService->addStudents($classroom, $request->safe()->only(['students'])['students']);

            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha creado la clase correctamente.');

            return Redirect::route('classrooms.index');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al crear la clase. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function edit(Classroom $classroom)
    {
        $classroom = new ClassroomEditResource($classroom);

        return Inertia::render('Classrooms/Edit', compact('classroom'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ClassroomUpdateRequest  $request
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(ClassroomUpdateRequest $request, Classroom $classroom, ClassroomService $classroomService)
    {
        try {
            $classroom = $classroomService->updateClassroom($classroom, $request->validated());

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha modificado la clase correctamente.');

            return Redirect::route('classrooms.edit', $classroom->id);
        } catch (\Throwable $th) {
            //throw $th;

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al modificar la clase. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom, ClassroomService $classroomService)
    {
        try {
            DB::beginTransaction();

            $classroomService->removeStudents($classroom);
            $classroomService->destroyClassroom($classroom);

            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha eliminado la clase correctamente.');

            return Redirect::back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al eliminar la clase. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function restore(Classroom $classroom, ClassroomService $classroomService)
    {
        try {
            $classroomService->restoreClassroom($classroom);

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha restaurado la clase correctamente.');

            return Redirect::back();
        } catch (\Throwable $th) {
            //throw $th;

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al restaurar la clase. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display a listing of students of the resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function students(Classroom $classroom)
    {
        return StudentResource::collection($classroom->classroomUsers()->with('user')->get());
    }

    /**
     * Display a listing of lessons of the resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function lessons(Classroom $classroom)
    {
        return LessonBasicResource::collection($classroom->lessons);
    }

    /**
     * Display a listing of lessons of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeStudents()
    {
        return ClassroomStudentsResource::collection(Classroom::activeWithStudents());
    }

}
