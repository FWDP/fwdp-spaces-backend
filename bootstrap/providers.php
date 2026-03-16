<?php

use App\Core\Messaging\Providers\MessagingServiceProvider;
use App\Core\Notifications\Providers\NotificationsServiceProvider;
use App\Core\Payments\Providers\PaymentServiceProvider;
use App\Modules\Crm\Providers\CrmServiceProvider;
use App\Modules\Inventory\Providers\InventoryServiceProvider;
use App\Modules\JobBoard\Providers\JobBoardServiceProvider;
use App\Modules\Learning\Providers\LearningServiceProvider;
use App\Modules\Marketplace\Providers\MarketplaceServiceProvider;
use App\Providers\ModuleServiceProvider;

return [
    MessagingServiceProvider::class,
    NotificationsServiceProvider::class,
    PaymentServiceProvider::class,
    ModuleServiceProvider::class,
    LearningServiceProvider::class,
    InventoryServiceProvider::class,
    CrmServiceProvider::class,
    MarketplaceServiceProvider::class,
    JobBoardServiceProvider::class,
];
