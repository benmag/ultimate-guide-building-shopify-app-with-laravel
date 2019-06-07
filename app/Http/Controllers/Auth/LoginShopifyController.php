<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Socialite;
use App\Store;
use App\UserProvider;
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

        $shopifyUser = Socialite::driver('shopify')->user();

        // Create user
        $user = User::firstOrCreate([
            'name' => $shopifyUser->nickname,
            'email' => $shopifyUser->email,
            'password' => '',
        ]);

        // Store the OAuth Identity
        UserProvider::firstOrCreate([
            'user_id' => $user->id,
            'provider' => 'shopify',
            'provider_user_id' => $shopifyUser->id,
            'provider_token' => $shopifyUser->token,
        ]);

        // Create shop
        $store = Store::firstOrCreate([
            'name' => $shopifyUser->name,
            'domain' => $shopifyUser->nickname,
        ]);

        // Attach store to user
        $store->users()->syncWithoutDetaching([$user->id]);


        // Login with Laravel's Authentication system
        Auth::login($user, true);

        // Setup uninstall webhook
        dispatch(new \App\Jobs\RegisterUninstallShopifyWebhook($store->domain, $shopifyUser->token, $store));

        return redirect('/home');

    }

}
