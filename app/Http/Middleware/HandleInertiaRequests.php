<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Laravel\Jetstream\Jetstream;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'user' => function () use ($request) {
                if (! $request->user()) {
                    return;
                }

                if (Jetstream::hasTeamFeatures() && $request->user()) {
                    $request->user()->currentTeam;
                }

                return array_merge($request->user()->toArray(), array_filter([
                    'all_teams' => Jetstream::hasTeamFeatures() ? $request->user()->allTeams()->values() : null,
                ]), [
                    'two_factor_enabled' => ! is_null($request->user()->two_factor_secret),
                ], [
                    'roles' => $request->user()->getRoleNames(),
                ]);
            },
            'errors' => $this->shareValidationErrors($request),
            'flash' => [
                'alert' => $this->shareFlashAlert($request)
            ],
        ]);
    }

    public function shareFlashAlert(Request $request)
    {
        if (! $request->session()->has('alert.style') || ! $request->session()->has('alert.message')) {
            return (object) [];
        }

        return [
            'style' => $request->session()->get('alert.style'),
            'message' => $request->session()->get('alert.message'),
        ];
    }

    public function shareValidationErrors(Request $request)
    {
        if (! $request->session()->has('errors')) {
            return (object) [];
        }

        return (object) collect($request->session()->get('errors')->getBags())->map(function ($bag) {
            return (object) collect($bag->messages())->map(function ($errors) {
                return $errors;
            })->toArray();
        })->pipe(function ($bags) use ($request) {
            if ($bags->has('default') && $request->header('x-inertia-error-bag')) {
                return [$request->header('x-inertia-error-bag') => $bags->get('default')];
            }

            if ($bags->has('default')) {
                return $bags->get('default');
            }

            return $bags->toArray();
        });
    }
}
