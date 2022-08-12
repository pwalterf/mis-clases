<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Resources\Admin\PaymentListResource;
use App\Http\Resources\Admin\StudentListResource;
use App\Http\Resources\Admin\SubscriptionResource;
use App\Models\ClassroomUser;
use App\Services\Admin\StudentService;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return StudentListResource::collection(ClassroomUser::withTrashed()->with('user', 'classroom')->get()->sortByDesc('classroom.created_at')->sortBy('classroom.deleted_at'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ClassroomUpdateRequest  $request
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(StudentUpdateRequest $request, ClassroomUser $student, StudentService $studentService, UserService $userService)
    {
        try {
            $userService->updateUser($student->user, $request->safe()->except('credit'));
            $student = $studentService->updateStudent($student, $request->safe()->only('credit'));

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha modificado el alumno correctamente.');

            return Redirect::back();
        } catch (\Throwable $th) {
            //throw $th;

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al modificar el alumno. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

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
        return PaymentListResource::collection($classroomUser->payments);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\ClassroomUser  $classroomUser
     * @return \Illuminate\Http\Response
     */
    public function subscription(ClassroomUser $classroomUser)
    {
        if (!$classroomUser->classroom->latestSubscription) return ['price_hr' => '-'];

        return new SubscriptionResource($classroomUser->classroom->latestSubscription);
    }
}
