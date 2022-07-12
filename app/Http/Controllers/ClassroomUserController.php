<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomUserStoreRequest;
use App\Http\Resources\ClassroomUsers\ClassroomUsersResource;
use App\Http\Resources\PaymentsResource;
use App\Http\Resources\SubscriptionsResource;
use App\Models\ClassroomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ClassroomUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ClassroomUsersResource::collection(ClassroomUser::with('user', 'classroom')->get()->sortBy('classroom_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ClassroomUserStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassroomUserStoreRequest $request)
    {
        try {
            $classroomUser = ClassroomUser::create($request->validated());

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha asociado al alumno correctamente.');

            return Redirect::back();
        } catch (\Throwable $th) {
            //throw $th;

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al asociar al alumno. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassroomUser  $classroomUser
     * @return \Illuminate\Http\Response
     */
    public function show(ClassroomUser $classroomUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassroomUser  $classroomUser
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassroomUser $classroomUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassroomUser  $classroomUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassroomUser $classroomUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassroomUser  $classroomUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassroomUser $classroomUser)
    {
        try {
            $classroomUser->delete();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha desvinculado al alumno correctamente.');

            return Redirect::back();
        } catch (\Throwable $th) {
            //throw $th;

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al desvincular al alumno. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\ClassroomUser  $classroomUser
     * @return \Illuminate\Http\Response
     */
    public function restore(ClassroomUser $classroomUser)
    {
        try {
            if (!$classroomUser->user->trashed()) {
                $classroomUser->restore();
    
                Session::flash('alert.style', 'exitoso');
                Session::flash('alert.message', 'Se ha vinculado al alumno correctamente.');
            } else {
                Session::flash('alert.style', 'error');
                Session::flash('alert.message', 'No se puede asociar al alumno porque su cuenta fue eliminada.');
            }
            
            return Redirect::back();
        } catch (\Throwable $th) {
            //throw $th;

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al vincular al alumno. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\ClassroomUser  $classroomUser
     * @return \Illuminate\Http\Response
     */
    public function payments(ClassroomUser $classroomUser)
    {
        return PaymentsResource::collection($classroomUser->payments);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\ClassroomUser  $classroomUser
     * @return \Illuminate\Http\Response
     */
    public function subscription(ClassroomUser $classroomUser)
    {
        return new SubscriptionsResource($classroomUser->classroom->subscriptions->first());
    }
}
