<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [ // request is the current request object
            'auth.user' => fn () => $request->user()
                ? $request->user()->only('id', 'name', 'email', 'avatar')
                : null, // if user is not logged in, return null
            'flash' => [
                'message' => fn () => $request->session()->get('success') ?? $request->session()->get('error'),
                // If "->with('success', 'User created')" it will put success message to message and to type 'success'
                //  for example: gets message from
                //  return redirect('/')->with('greet', 'Welcome to our site!'); in AuthController,
                // and then I can use it in my homePage to say that registration was successful ($page.props.flash.greet)
                'type' => fn () => $request->session()->has('success') ? 'success' : ($request->session()->has('error') ? 'error' : null),
            ],
        ]);
    }
}
