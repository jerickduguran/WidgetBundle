<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder; 
use Symfony\Component\DependencyInjection\Reference;

class WidgetTagCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container) 
    {   
        $definition = $container->findDefinition(
            'mosaic_widget.editor.manager.widget'
        );
        
        $taggedServices = $container->findTaggedServiceIds(
            'mosaic_widget.widget'
        );  

        $definition->addMethodCall("setRouter",array(new Reference("router")));
		
        $widgetParams = array();
        if ($container->hasParameter("mosaic_widget.editor.widget_items")) {
                $widgetParams = $container->getParameter("mosaic_widget.editor.widget_items");
        }

        $definition->addMethodCall('setContainer', array(new Reference('service_container'))); 
        
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes){
                $definition->addMethodCall(
                    'addWidget',
                    array(new Reference($id), $id, $widgetParams)
                );
            }
        }   
    } 
}
