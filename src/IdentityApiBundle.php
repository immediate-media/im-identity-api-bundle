<?php

declare(strict_types=1);

namespace IM\Fabric\Bundle\IdentityApiBundle;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class IdentityApiBundle extends AbstractBundle
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function loadExtension(
        array $config,
        ContainerConfigurator $configurator,
        ContainerBuilder $builder
    ): void {
        $configurator->import('../config/services.yaml');
    }
}
