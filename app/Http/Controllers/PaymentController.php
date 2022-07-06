<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentStoreRequest;
use App\Http\Requests\PaymentUpdateRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\Payments\ClassroomUsersResource;
use App\Http\Resources\PaymentsResource;
use App\Models\ClassroomUser;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = PaymentsResource::collection(Payment::all());

        return Inertia::render('Payments/Index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\ClassroomUser  $classroomUser
     * @return \Illuminate\Http\Response
     */
    public function create(ClassroomUser $classroomUser = null)
    {
        $student = [];
        if ($classroomUser) {
            $student[] = new ClassroomUsersResource($classroomUser);
        }

        return Inertia::render('Payments/Create', compact('student'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PaymentStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedStudents = $request->safe()->only(['students'])['students'];
            $payment = Payment::create($request->safe()->except(['students']));

            // students contiene [classroom_user_id => [new_credit]] por cada estudiante asociado al pago
            $payment->classroomUsers()->attach($validatedStudents);
            foreach ($validatedStudents as $id => $credit) {
                $classroomUser = ClassroomUser::find($id);
                $classroomUser->update(['credit' => $classroomUser->credit + $credit['new_credit']]);
            }

            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha registrado el pago correctamente.');

            return Redirect::route('payments.edit', $payment->id);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            
            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al registrar el pago. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        $payment = new PaymentResource($payment);
        return Inertia::render('Payments/Edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PaymentUpdateRequest  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentUpdateRequest $request, Payment $payment)
    {
        try {
            DB::beginTransaction();

            $validatedStudents = $request->safe()->only(['students'])['students'];
            $validatedOriginal = $request->safe()->only(['original_students'])['original_students'];

            $payment->update($request->safe()->except(['students', 'original_students']));

            if (collect($validatedOriginal)->diffAssoc($validatedStudents) != []) {
                $payment->classroomUsers()->sync(collect($validatedStudents)
                    ->mapWithKeys(function ($new_credit, $id) {
                        return [$id => ['new_credit' => $new_credit]];
                    })
                );

                foreach ($validatedOriginal as $id => $new_credit) {
                    $classroomUser = ClassroomUser::find($id);
                    $classroomUser->update(['credit' => $classroomUser->credit - $new_credit]);
                }

                foreach ($validatedStudents as $id => $new_credit) {
                    $classroomUser = ClassroomUser::find($id);
                    $classroomUser->update(['credit' => $classroomUser->credit + $new_credit]);
                }
            }

            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha modificado el pago correctamente.');

            return Redirect::route('payments.edit', $payment->id);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al modificar el pago. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        try {
            DB::beginTransaction();

            foreach ($payment->classroomUsers as $student) {
                $student->update(['credit' => $student->credit - $student->studentPayment->new_credit]);
            }

            $payment->delete();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha eliminado el pago correctamente.');

            DB::commit();

            return Redirect::route('payments.index');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al eliminar el pago. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.');

            return Redirect::back()->withInput();
        }
    }
}
