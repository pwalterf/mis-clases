<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Requests\PaymentUpdateRequest;
use App\Http\Resources\Admin\PaymentEditResource;
use App\Http\Resources\Admin\PaymentListResource;
use App\Http\Resources\Admin\StudentPaymentResource;
use App\Models\ClassroomUser;
use App\Models\Payment;
use App\Services\Admin\PaymentService;
use App\Services\Admin\StudentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Illuminate\Support\Arr;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = PaymentListResource::collection(Payment::all());

        return Inertia::render('Payments/Index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\ClassroomUser  $classroomUser
     * @return \Illuminate\Http\Response
     */
    public function create(ClassroomUser $student = null)
    {
        $student = [];
        if ($student) {
            $student = new StudentPaymentResource($student);
        }

        return Inertia::render('Payments/Create', compact('student'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PaymentStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentStoreRequest $request, PaymentService $paymentService, StudentService $studentService)
    {
        try {
            DB::beginTransaction();

            $validatedStudents = $request->safe()->only(['students'])['students'];
            $payment = $paymentService->createPayment($request->safe()->except(['students']));

            // students contiene [classroom_user_id => [new_credit]] por cada estudiante asociado al pago
            $paymentService->syncStudents($payment, $request->safe()->only(['students'])['students']);
            
            foreach ($validatedStudents as $id => $credit) {
                $studentService->updateCredit(ClassroomUser::find($id), $credit['new_credit']);
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        $payment = new PaymentEditResource($payment);
        return Inertia::render('Payments/Edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PaymentUpdateRequest  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentUpdateRequest $request, Payment $payment, PaymentService $paymentService, StudentService $studentService)
    {
        try {
            DB::beginTransaction();

            $validatedStudents = $request->safe()->only(['students'])['students'];
            $originalStudents = $paymentService->formatStudentsCreditArray($payment);

            $payment = $paymentService->updatePayment($payment, $request->safe()->except(['students']));
            $paymentService->syncStudents($payment, $validatedStudents);
            $studentService->syncCredit($paymentService->calculateStudentsCredit($originalStudents, $validatedStudents));

            DB::commit();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha modificado el pago correctamente.');

            return Redirect::route('payments.edit', $payment->id);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            Session::flash('alert.style', 'error');
            Session::flash('alert.message', 'Ha ocurrido un error al modificar el pago. Por favor vuelva a intentar y si el problema persiste, comuníquese con el administrador.'.$th);

            return Redirect::back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment, PaymentService $paymentService, StudentService $studentService)
    {
        try {
            DB::beginTransaction();

            foreach ($payment->classroomUsers as $student) {
                $studentService->updateCredit($student, -$student->studentPayment->new_credit);
            }

            $paymentService->destroyPayment($payment);

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

    /**
     * Display a listing of students of the resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function students(Payment $payment)
    {
        return StudentPaymentResource::collection($payment->classroomUsers()->with('user', 'classroom')->get());
    }
}
