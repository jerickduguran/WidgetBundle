<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder; 
use Symfony\Component\DependencyInjection\Reference;

class OverrideFormatterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container) 
    {   
        if ($container->hasDefinition('mosaic_formatter.formatter_type_selector')) {
                $definition = $container->getDefinition('mosaic_formatter.formatter_type_selector'); 
                $definition->setClass('Groupm\Mosaic\Bundle\WidgetBundle\Form\Type\FormatterType');
                $definition->addMethodCall('setPluginManager', array(new Reference('ivory_ck_editor.plugin_manager')));
        }

        if ($container->hasDefinition('ivory_ckeditor')) {
                $definition_ckeditor = $container->getDefinition('ivory_ck_editor.templating.helper'); 
                $definition_ckeditor->setClass('Groupm\Mosaic\Bundle\WidgetBundle\Helper\CKEditorHelper');
        }  
        if ($container->hasDefinition('sonata.page.block.container')) { 
                $definition_ckeditor = $container->getDefinition('sonata.page.block.container');  
                $definition_ckeditor->setClass('Groupm\Mosaic\Bundle\WidgetBundle\Block\ContainerBlockService');
        }
        
        if ($container->hasDefinition('ivory_ck_editor.renderer')) { 
                $definition_ckeditor = $container->getDefinition('ivory_ck_editor.renderer');  
                $definition_ckeditor->setClass('Groupm\Mosaic\Bundle\WidgetBundle\Helper\CKEditorRenderer');
                $definition_ckeditor->addMethodCall('setRequestStack', array(new Reference('request_stack')));
        }  
    } 
}
