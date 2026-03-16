<?php

return [
    App\Core\Messaging\Providers\MessagingServiceProvider::class,
    App\Core\Notifications\Providers\NotificationsServiceProvider::class,
    App\Core\Payments\Providers\PaymentServiceProvider::class,
    App\Providers\ModuleServiceProvider::class,
	App\Modules\Learning\Providers\LearningServiceProvider::class,
	App\Modules\Inventory\Providers\InventoryServiceProvider::class,
	App\Modules\Crm\Providers\CrmServiceProvider::class,
	App\Modules\Marketplace\Providers\MarketplaceServiceProvider::class,
	App\Modules\JobBoard\Providers\JobBoardServiceProvider::class,
];