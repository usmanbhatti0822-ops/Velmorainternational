<?php

namespace App\Console\Commands;

use Database\Seeders\VelmoraCatalogSeeder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('velmora:seed-demo-catalog')]
#[Description('Add any missing demo catalog entries without overwriting existing records')]
class SeedDemoCatalogCommand extends Command
{
    public function handle(): int
    {
        $exitCode = $this->call('db:seed', [
            '--class' => VelmoraCatalogSeeder::class,
            '--force' => true,
        ]);

        return $exitCode === self::SUCCESS ? self::SUCCESS : self::FAILURE;
    }
}
