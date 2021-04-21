<?php

declare (strict_types=1);

namespace App\Config\Application\Bootstrap;

use App\Config\Application\Files\Path;
use App\Config\Application\Files\ProjectPath;
use App\Infrastructure\Facades\Container;
use DI\ContainerBuilder;
use Exception;
use Psr\Container\ContainerInterface;

class AppContainerBuilder extends ContainerBuilder
{
    /**
     * Creates a new instance
     */
    public function __construct()
    {
        parent::__construct();

        $this->useAutowiring(true)
            ->useAnnotations(false)
            ->addDefinitions(InjectionDefinitionsGetter::get())
            ->writeProxiesToFile(true, ProjectPath::get(Path::CONTAINER_PROXIES));

        if (getenv('IS_PRODUCTION')) {
            $this->enableProductionSettings();
        }
    }

    /**
     * Create a new DI Container
     *
     * Lifecycle: Transient
     *
     * @return ContainerInterface
     * @throws Exception
     */
    public static function create(): ContainerInterface
    {
        $builder = new static();

        return $builder->build();
    }

    /**
     * Init (or reset) the entire container
     *
     * @return void
     * @throws Exception
     */
    public static function initOrReset(): void
    {
        $newContainer = static::create();

        Container::setInstance($newContainer);
    }

    private function enableProductionSettings(): void
    {
        $this->enableCompilation(ProjectPath::get(Path::DI_PROXIES));
        $this->enableDefinitionCache();
    }
}
