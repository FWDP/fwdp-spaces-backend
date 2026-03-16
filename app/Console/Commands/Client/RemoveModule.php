<?php

namespace App\Console\Commands\Client;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RemoveModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:remove {name} {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a modular-monolith module and its files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $basePath = app_path("Modules/{$name}");

        if (! File::exists($basePath)) {
            $this->error("Module {$name} does not exist.");
            return CommandAlias::FAILURE;
        }

        if (! $this->option('force')) {
            $confirmed = $this->confirm(
                "Are you sure you want to permanently delete the {$name} module?"
            );

            if (! $confirmed) {
                $this->info("Aborted. Nothing was deleted.");
                return Command::SUCCESS;
            }
        }

        $this->warn("Removing module: {$name}");

        // 1) Delete module directory
        File::deleteDirectory($basePath);

        // 2) Remind user to remove provider
        $this->line('');
        $this->info("Module files removed.");

        return Command::SUCCESS;

    }
}
