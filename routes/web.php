<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('login/shopify', 'Auth\LoginShopifyController@redirectToProvider')->name('login.shopify');
Route::get('login/shopify/callback', 'Auth\LoginShopifyController@handleProviderCallback');
Route::get('shopify/subscribe', function(\Illuminate\Http\Request $request) {


    $store = \App\Store::find(1);
    $user = auth()->user()->providers->where('provider', 'shopify')->first();
    $shopify = \Shopify::retrieve($store->domain, $user->provider_token);

    $activated = \ShopifyBilling::driver('RecurringBilling')
        ->activate($store->domain, $user->provider_token, $request->get('charge_id'));

    $response = array_get($activated->getActivated(), 'recurring_application_charge');

    $store = \App\Store::find(1);

    \App\Charge::create([
        'store_id' => $store->id,
        'name' => 'default',
        'shopify_charge_id' => $request->get('charge_id'),
        'shopify_plan' => array_get($response, 'name'),
        'quantity' => 1,
        'charge_type' => \App\Charge::CHARGE_RECURRING,
        'test' => array_get($response, 'test'),
        'trial_ends_at' => array_get($response, 'trial_ends_on'),
    ]);

    return redirect('/home');

})->name('shopify.subscribe');
