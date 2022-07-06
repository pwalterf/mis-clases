<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionStoreRequest;
use App\Models\Classroom;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function index(Classroom $classroom)
    {
        $subscriptions = $classroom->subscriptions;

        return Inertia::render('Subscriptions/Index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Subscription/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SubscriptionStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionStoreRequest $request, Classroom $classroom)
    {
        try {
            $validatedSubscription = $request->validated();
            $validatedSubscription['classroom_id'] = $classroom->id;

            $subscription = Subscription::create($validatedSubscription);

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha creado la suscripción correctamente.');

            return Redirect::back()->with('classroom', $classroom);
        } catch (\Throwable $th) {
            //throw $th;

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \SubscriptionStoreRequest  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(SubscriptionStoreRequest $request, Subscription $subscription)
    {
        try {
            $subscription->update($request->validated());

            return response($subscription, 200);
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
    public function destroy(Subscription $subscription)
    {
        try {
            $subscription->delete();

            Session::flash('alert.style', 'exitoso');
            Session::flash('alert.message', 'Se ha eliminado la suscripción correctamente.');

            return Redirect::back();
        } catch (\Throwable $th) {
            //throw $th;

            return Redirect::back()->withInput();
        }
    }
}
