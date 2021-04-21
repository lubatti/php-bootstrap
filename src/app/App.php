<?php

declare (strict_types=1);

namespace App;

use App\Config\Application\Bootstrap\AppContainerBuilder;

class App
{
    /**
     * Boot the application
     */
    public function boot(): void
    {
        AppContainerBuilder::initOrReset();
    }

    /**
     * Run application
     */
    public function run()
    {
        echo 'Hello world' . PHP_EOL;
    }

    /**
     * Initializes the application
     */
    public static function initiate(): self
    {
        $app = new self();
        $app->boot();

        return $app;
    }
}
