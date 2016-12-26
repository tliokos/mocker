<?php

namespace Mocker\Provider;

use Dotenv\Dotenv;
use Pimple\{Container, ServiceProviderInterface};

class DotEnvLoader implements ServiceProviderInterface
{
    /**
     * @var Dotenv
     */
    private $dotEnvReader;

    /**
     * EnvironmentalVariablesLoader constructor.
     * @param Dotenv $dotEnvLoader
     */
    public function __construct(Dotenv $dotEnvLoader)
    {
        $this->dotEnvReader = $dotEnvLoader;
    }

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $container An Container instance
     */
    public function register(Container $container)
    {
        $this->dotEnvReader->overload();
    }
}