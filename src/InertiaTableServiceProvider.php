<?php

namespace MNPLUS\InertiaTable;

use Inertia\Response;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MNPLUS\InertiaTable\Commands\MakeTableResourceCommand;

class InertiaTableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('inertia-table')
            ->hasCommand(MakeTableResourceCommand::class);
    }

    public function boot(): void
    {
        // Ensure Response is the Inertia\Response
        Response::macro('getTableProps', function () {
            /** @var Response $this */
            return $this->props['tableProps'] ?? [];
        });

        Response::macro('table', function ($table) {
            /** @var Response $this */
            // Ensure $table->build($this) returns Response
            return $table->build($this);
        });

        Response::macro('tables', function (array $tables) {
            /** @var Response $this */
            $response = $this;

            collect($tables)->each(function ($table) use ($response) {
                // Ensure $table->build($response) returns void or Response
                $table->build($response);
            });

            return $response;
        });
    }
}
