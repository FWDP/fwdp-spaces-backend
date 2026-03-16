<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ModuleList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:list {--enabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all modules and their status.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $q = DB::table('modules');

        if ($this->option('enabled')) {
            $q->where('enabled', true);
        }

        $modules = $q
            ->orderBy('name')
            ->get([
                'name',
                'enabled',
                'created_at',
            ]);

        if (empty($modules)) {
            $this->warn('No modules found.');
            return CommandAlias::SUCCESS;
        }

        $this->info("\n Installed Modules: \n");

        $this->table(
            ['Name', 'Enabled', 'Installed at'],
            $modules->map(function ($item) {
                return [
                    'Module' => $item->name,
                    'Status' => $item->enabled ? 'Enabled' : 'Disabled',
                    'Installed At' => $item->created_at];
            })->toArray(),
        );

        return CommandAlias::SUCCESS;
    }
}
