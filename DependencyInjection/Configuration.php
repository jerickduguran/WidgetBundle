<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('mosaic_widget'); 

        $this->addEditorWidgetSettings($rootNode);  
        
        return $treeBuilder;
    }
	
    protected function addEditorWidgetSettings(ArrayNodeDefinition $node)
    {
        $node->children() 
            ->arrayNode('editor')  
                ->children()      
                    ->arrayNode('widget') 
                         ->children()     
                            ->scalarNode('class')
                                ->defaultValue('mosaic-widget') 
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('name')
                                ->defaultValue('mosaic-widget') 
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->arrayNode('items') 
                                ->prototype('array')   
                                    ->children()   
                                        ->scalarNode('name')->isRequired()->end()
                                        ->arrayNode('settings')
                                            ->prototype('variable')->end()
                                            ->isRequired()
                                            ->cannotBeEmpty()
                                        ->end()
                                    ->end() 
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()  
            ->end()
        ->end();  
    } 
}
