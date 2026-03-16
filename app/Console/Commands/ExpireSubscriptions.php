<?php

namespace App\Console\Commands;

use App\Core\Subscriptions\Models\Subscription;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Expire subscriptions when End Date approach.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Subscription::query()
            ->where('subscriptions.status', '=', 'active')
            ->whereNotNull('subscriptions.end_date')
            ->whereDate('subscriptions.end_date', '<', now())
            ->update(['status'  => 'expired']);
        ;

        $this->info('Subscription expired');

        return CommandAlias::SUCCESS;
    }
}
