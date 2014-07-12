<?php

namespace Matmar10\Bundle\RestApiBundle\DependencyInjection;

use Exception;

use JMS\SerializerBundle\DependencyInjection\Configuration as JMSSerializerBundleConfiguration;
use Matmar10\Bundle\RestApiBundle\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Config\FileLocator;

class Matmar10RestApiExtension extends Extension implements PrependExtensionInterface
{

    public function prepend(ContainerBuilder $container)
    {
        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');
        // determine if AcmeGoodbyeBundle is registered
        if (!isset($bundles['JMSSerializerBundle'])) {
            throw new Exception('JMSSerializerBundle is not registered (did you add it to app/AppKernel.php?)');
        }

        $yamlParser = new Parser();
        $yamlConfig = $yamlParser->parse(file_get_contents(__DIR__ . '/../Resources/config/jms_serializer_config.yml'));
        $config = $this->processConfiguration(new JMSSerializerBundleConfiguration(), $yamlConfig);
        $container->prependExtensionConfig('jms_serializer', $config);
    }

    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('matmar10_rest_api.default_success_status_code', $config['success_status_code']);
        $container->setParameter('matmar10_rest_api.default_exception_status_code', $config['exception_status_code']);
        $container->setParameter('matmar10_rest_api.default_serialize_type', $config['serialize_type']);

        $groups = $config['groups'];
        if($container->getParameter('kernel.debug')) {
            $groups = array_merge($groups, $config['groups_debug']);
        }
        $container->setParameter('matmar10_rest_api.default_serialize_groups', $groups);

        $contentTypes = array_merge($container->getParameter('matmar10_rest_api.default_content_types'), $config['content_types']);
        $container->setParameter('matmar10_rest_api.content_types', $contentTypes);
    }

    public function getAlias()
    {
        return 'matmar10_rest_api';
    }
}
