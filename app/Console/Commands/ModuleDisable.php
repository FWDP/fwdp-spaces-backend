<?php

namespace App\Console\Commands;

use App\Core\Support\ModuleRegistry;
use App\Core\Support\Modules\ModuleRecord;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ModuleDisable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diable a module.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {

        ModuleRecord::query()
            ->where('name', Str::lower($this->argument('module')))
            ->update(['enabled' => false]);

        $this->info("Module [{$this->argument('module')}] successfully disabled.");

        Artisan::call('module:rollback', ['module' => $this->argument('module')]);

        $this->removeProviderFromBootstrap($this->argument('module'));

        return CommandAlias::SUCCESS;
    }
    public function removeProviderFromBootstrap(string $slug): void
    {
        $studly = Str::studly($slug);

        $providerLine = match (true) {
            is_dir(app_path("Modules/{$studly}/Providers"))
            =>"App\\Modules\\{$studly}\\Providers\\{$studly}ServiceProvider::class",
            default => null
        };

        if (!$providerLine){
            $this->warn("No matching ServiceProvider found for [{$studly}] - skipping.");
        }

        if (!"bootstrap/providers.php"
                |> base_path(...)
                |> file_get_contents(...)
                |> (fn($x) => Str::contains($x, $providerLine))) {
            $this->info("Provider not present - nothing to remove.");
        } else {
            $escapeProvider = preg_quote($providerLine, "/");

            $contents = "bootstrap/providers.php"
                    |> base_path(...)
                    |> file_get_contents(...)
                    |> (fn($x) => preg_replace("/\s*{$escapeProvider}\s*,?\s*\n/", "", $x));

            file_put_contents(base_path(
                "bootstrap/providers.php"),
                preg_replace(
                    "/(App\\\\[^n]+ServiceProvider::class),\s*];$/m",
                    "$1\n];",
                    $contents
                )
            );

            $this->info("Provider [{$this->argument('module')}] successfully removed.");
        }
    }
}
