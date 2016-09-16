<?php

namespace ArsThanea\PageMediaSetBundle\DependencyInjection;

use ArsThanea\PageMediaSetBundle\Service\MediaFormat;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PageMediaSetExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $configs = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if ($configs['indexer']) {
            $loader->load('indexer.yml');
        }

        $container->setParameter('page_media_set.types', $configs['types']);

        $repository = $container->getDefinition('page_media_set.format_repository');
        foreach ($configs['formats'] as $name => $format) {
            $id = 'page_media_set.format.' . $name;
            $container->setDefinition($id, (new Definition(MediaFormat::class, [
                $name,
                $format['min_width'],
                $format['min_height'],
                $format['max_width'],
                $format['max_height']
            ]))->setPublic(false));
            $repository->addMethodCall('add', [new Reference($id)]);
        }
    }
}
