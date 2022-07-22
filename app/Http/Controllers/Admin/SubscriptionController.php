<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionStoreRequest;
use App\Models\Classroom;
use App\Models\Subscription;
use App\Services\Admin\SubscriptionService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SubscriptionStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionStoreRequest $request, Classroom $classroom, SubscriptionService $subscriptionService)
    {
        try {
            $subscriptionService->createSubscription($classroom, $request->validated());

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha creado la suscripción correctamente.');

            return Redirect::back()->with('classroom', $classroom);
        } catch (\Throwable $th) {
            //throw $th;

            return Redirect::back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription, SubscriptionService $subscriptionService)
    {
        try {
            $subscriptionService->destroySubscription($subscription);

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha eliminado la suscripción correctamente.');

            return Redirect::back();
        } catch (\Throwable $th) {
            //throw $th;

            return Redirect::back()->withInput();
        }
    }
}
