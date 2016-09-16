<?php

namespace ArsThanea\PageMediaSetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link
 * http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('page_media_set');

        $rootNode->children()->booleanNode('indexer')->defaultFalse();

        /** @var ArrayNodeDefinition $types */
        $types = $rootNode->children()->arrayNode('types');
        $types->defaultValue([]);
        $types = $types->prototype('array');
        $types->prototype('scalar');

        $formats = $rootNode->children()->arrayNode('formats');
        $formats->isRequired();
        $formats->requiresAtLeastOneElement();
        $formats->beforeNormalization()->ifNull()->then(function () { return []; });


        /** @var ArrayNodeDefinition $prototype */
        $prototype = $formats->prototype('array');

        $prototype->children()->scalarNode('min_width')->defaultNull();
        $prototype->children()->scalarNode('min_height')->defaultNull();
        $prototype->children()->scalarNode('max_width')->defaultNull();
        $prototype->children()->scalarNode('max_height')->defaultNull();

        return $treeBuilder;
    }
}
