<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the provider's authentication page.
     */
    public function loginSocial(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the provider.
     */
    public function callbackSocial(Request $request, string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        // Retrieve user data from the provider
        $providerUser = Socialite::driver($provider)->user();

        // Find or create the user
        $user = User::firstOrCreate(
            ['email' => $providerUser->getEmail()],
            ['password' => Str::password()]
        );

        // Prepare data to update or create
        $data = [$provider . '_id' => $providerUser->getId()];

        // If the user was just created, set their name and fire the Registered event
        if ($user->wasRecentlyCreated) {
            $data['name'] = $providerUser->getName() ?? $providerUser->getNickname();
            Event::dispatch(new Registered($user));
        }

        // Update the user with provider-specific data
        $user->update($data);

        // Log the user in
        Auth::login($user, remember: true);

        // Redirect to the intended URL or a default route
        return redirect()->intended('/dashboard'); // Change '/dashboard' to your desired home route
    }

    /**
     * Validate the provider parameter.
     */
    protected function validateProvider(string $provider): void
    {
        if (!in_array($provider, ['facebook', 'google', 'github'])) {
            abort(404, 'Invalid provider');
        }
    }
}
