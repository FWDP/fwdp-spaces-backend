<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Core\Auth\Providers\AuthServiceProvider::class,
    App\Core\Profile\Providers\ProfileServiceProvider::class,
    App\Core\Subscriptions\Providers\SubscriptionsServiceProvider::class,
    App\Core\Payments\Providers\PaymentServiceProvider::class
];
