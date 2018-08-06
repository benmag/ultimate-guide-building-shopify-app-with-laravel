<?php

namespace App\Http\Middleware;

use App\Store;
use App\Charge;

class VerifyStoreIsSubscribed
{
    /**
     * Verify the incoming request's user has a subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $subscription
     * @param  string  $plan
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next, $subscription = 'default', $plan = null)
    {

    $store = Store::find(1);

    if ($this->subscribed($store, $subscription, $plan, func_num_args() === 2)) {
        return $next($request);
    }

    if($request->ajax() || $request->wantsJson()) {
        response('Subscription Required.', 402);
    }

    $user = auth()->user()->providers->where('provider', 'shopify')->first();
    $shopify = \Shopify::retrieve($store->domain, $user->provider_token);

    $options = [
        'name' => 'starter plan',
        'price' => '10',
        'trial_days' => 7,
        'return_url' => route('shopify.subscribe'),
    ];

    if(\App::environment('local')) {
        $options['test'] = true;
    }

    return \ShopifyBilling::driver('RecurringBilling')
        ->create($shopify, $options)
        ->redirect()
        ->with('user', $user);

    }

    /**
     * Determine if the given user is subscribed to the given plan.
     *
     * @param  \App\Store  $store
     * @param  string  $subscription
     * @param  string  $plan
     * @param  bool  $defaultSubscription
     * @return bool
     */
    protected function subscribed($store, $subscription, $plan, $defaultSubscription)
    {
        if (! $store) {
            return false;
        }

        return ($defaultSubscription && $store->onGenericTrial()) ||
                $store->subscribed($subscription, $plan);
    }


}
