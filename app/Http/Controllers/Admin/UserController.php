<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\Admin\UserBasicResource;
use App\Http\Resources\Admin\UserListResource;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = UserListResource::collection(User::withTrashed()->with('roles')->get());

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
    public function store(UserStoreRequest $request, UserService $userService)
    {
        try {
            DB::beginTransaction();

            $user = $userService->createUser($request->safe()->except('role'));
            $userService->assignRole($user, $request->safe()->only('role'));

            //Notification::send($user, new NewUser($password));

            DB::commit();

            // Mensajes de exito
            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'El usuario se cargó al sistema correctamente.');

            return Redirect::route('users.index');
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $userData = new UserListResource($user);
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
    public function update(UserUpdateRequest $request, User $user, UserService $userService)
    {
        try {
            DB::beginTransaction();

            $userService->updateUser($user, $request->validated());

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
    public function destroy(User $user, UserService $userService)
    {
        try {
            DB::beginTransaction();

            if (!$userService->destroyUser($user)) {
                Session::flash('alert.style', 'error');
                Session::flash('alert.message', 'No puede eliminar su propia cuenta.');

                return Redirect::back();
            }
            $userService->destroyStudent($user);

            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se dió de baja al usuario correctamente.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

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
    public function restore(User $user, UserService $userService)
    {
        try {
            if (!$userService->restoreUser($user)) {
                Session::flash('alert.style', 'error');
                Session::flash('alert.message', 'No puede restaurar su propia cuenta.');

                return Redirect::back();
            }

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
        return UserBasicResource::collection(User::getStudents());
    }
}
