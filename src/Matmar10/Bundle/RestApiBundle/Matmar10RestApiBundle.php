<?php

namespace Matmar10\Bundle\RestApiBundle;

use Matmar10\Bundle\RestApiBundle\DependencyInjection\Matmar10RestApiExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Matmar10RestApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->registerExtension(new Matmar10RestApiExtension());
    }
}