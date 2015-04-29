<?php

namespace Fenrizbes\PublicationsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FenrizbesPublicationsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('fp.date_format',              $config['date_format']);
        $container->setParameter('fp.datetime_format',          $config['datetime_format']);
        $container->setParameter('fp.sonata_admin.group_label', $config['sonata_admin']['group_label']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (isset($bundles['SonataAdminBundle'])) {
            $loader->load('sonata_admin.yml');
        }

        if (is_null($config['sonata_admin']['content_editor']) && isset($bundles['IvoryCKEditorBundle'])) {
            $config['sonata_admin']['content_editor'] = 'ckeditor';
        }

        $container->setParameter('fp.sonata_admin.content_editor', $config['sonata_admin']['content_editor']);
    }
}
