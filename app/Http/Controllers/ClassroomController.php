<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomStoreRequest;
use App\Http\Requests\ClassroomUpdateRequest;
use App\Http\Resources\Classroom\ClassroomResource;
use App\Http\Resources\Classroom\ClassroomsResource;
use App\Models\Classroom;
use App\Models\Subscription;
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
        $classrooms = ClassroomsResource::collection(Classroom::addSelect([
            'price_hr' => Subscription::select('price_hr')
                ->whereColumn('classroom_id', 'classrooms.id')
                ->latest()
                ->limit(1)
            ])->get());
        
        //$classrooms = Classroom::with('subscriptions')->get();
        //$classrooms = ClassroomResource::collection(Classroom::with('currentSubs')->get());
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
    public function store(ClassroomStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $validatedClassroom = $request->safe()->only(['name', 'description']);
            $validatedSubs = $request->safe()->only(['price_hr']);
            $validatedStudents = $request->safe()->only(['students']);

            $classroom = Classroom::create($validatedClassroom);
            $subscription = $classroom->subscriptions()->create($validatedSubs);
            $classroomUsers = $classroom->classroomUsers()->createMany($validatedStudents['students']);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function edit(Classroom $classroom)
    {
        $classroom = new ClassroomResource($classroom);

        return Inertia::render('Classrooms/Edit', compact('classroom'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ClassroomUpdateRequest  $request
     * @param  Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(ClassroomUpdateRequest $request, Classroom $classroom)
    {
        try {
            $classroom->update($request->validated());

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha modificado la clase correctamente.');

            return Redirect::route('classrooms.edit', $classroom);
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
     * @param  Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom)
    {
        try {
            $classroom->delete();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha eliminado la clase correctamente.');

            return Redirect::route('classrooms.index');
        } catch (\Throwable $th) {
            //throw $th;

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al eliminar la clase. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }
}
