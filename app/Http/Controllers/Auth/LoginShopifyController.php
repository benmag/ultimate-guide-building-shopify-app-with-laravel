<?php

namespace App\Http\Controllers\Auth;

use Authl
use Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginShopifyController extends Controller
{

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(Request $request)
    {

        $this->validate($request, [
            'domain' => 'string|required'
        ]);

        $config = new \SocialiteProviders\Manager\Config(
            env('SHOPIFY_KEY'),
            env('SHOPIFY_SECRET'),
            env('SHOPIFY_REDIRECT'),
            ['subdomain' => $request->get('domain')]
        );

        return Socialite::with('shopify')
            ->setConfig($config)
            ->scopes(['read_products','write_products'])
            ->redirect();

    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('shopify')->user();

        /*
            This is where it can get tricky. You need to add a logic where you check if user
            is already registered using regular email. In that case just add his social details
            and let him login, otherwise add new entry in the social table. This way user account
            won't be duplicated if his social account email and regular account email is same.
        */

        // $authUser = $this->findOrCreateUser($user, $provider);
        // Auth::login($user, true);

        return redirect('/home');

    }

}
