<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\StudentsResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        $users = User::with('roles')->get();

        foreach ($users as $key => $user) {
            $user['roleNames'] = $user->roles->pluck('name')->join(', ', ' y ');
        }
        */

        $users = UserResource::collection(User::withTrashed()->with('roles')->get());

        return Inertia::render('Users/Index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all()->pluck('name');

        return Inertia::render('Users/Create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedUser = $request->safe()->except('role');
            $validatedRole = $request->safe()->only('role');

            //$validatedUser['password'] = Hash::make($validatedUser['password']);
            $validatedUser['password'] = Hash::make(Str::random(8));

            $user = User::create($validatedUser);

            $user->assignRole($validatedRole['role']);
            //Notification::send($user, new NewUser($password));

            DB::commit();

            // Mensajes de exito
            //Session::flash('alert.style', 'exitoso');
            //Session::flash('alert.message', 'El usuario se cargó al sistema correctamente.');

            return Redirect::route('users.index')->with(['alert.style' => 'exitoso', 'alert.message' => 'El usuario se cargó al sistema correctamente.']);
        } catch (\Throwable $th) {
            DB::rollBack();

            // Mensaje de error
            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al crear el usuario. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $userData = new UserResource($user);
        $roles = Role::all()->pluck('name');

        return Inertia::render('Users/Edit', compact('userData', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            $validatedUser = $request->validated();
            //$validatedRole = $request->safe()->only('role');

            //$validatedUser['password'] = Hash::make($validatedUser['password']);

            $user->update($validatedUser);

            //$user->assignRole($validatedRole['role']);
            //Notification::send($user, new NewUser($password));

            DB::commit();

            // Mensajes de exito
            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se modificaron los datos del usuario correctamente.');

            return Redirect::back();
        } catch (\Throwable $th) {
            DB::rollBack();

            // Mensaje de error
            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al modificar los datos. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            if (auth()->user()->id === $user->id) {
                return Redirect::back();
            }

            $user->delete();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se dió de baja al usuario correctamente.');
        } catch (\Throwable $th) {
            //throw $th;
            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al dar de baja al usuario. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');
        }

        return Redirect::back();
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function restore(User $user)
    {
        try {
            if (auth()->user()->id === $user->id) {
                return Redirect::back();
            }

            $user->restore();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se restauró al usuario correctamente.');
        } catch (\Throwable $th) {
            //throw $th;
            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al restaurar al usuario. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');
        }

        return Redirect::back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function students()
    {
        return StudentsResource::collection(User::whereRelation('roles', 'name', 'alumno')->get());
    }
}
