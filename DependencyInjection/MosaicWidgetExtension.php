<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class MosaicWidgetExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs); 
        
        if (isset($config['editor']) && isset($config['editor']['widget']) && isset($config['editor']['class'])) {
                $container->setParameter('mosaic_widget.editor.widget_class', $config['editor']['widget']['class']);  
        } else {
                $container->setParameter('mosaic_widget.editor.widget_class', 'mosaic-widget');
        }

        if (isset($config['editor']) && isset($config['editor']['widget']) && isset($config['editor']['name'])) {
                $container->setParameter('mosaic_widget.editor.widget_name', $config['editor']['widget']['name']);  
        } else {
                $container->setParameter('mosaic_widget.editor.widget_name', 'mosaic-widget');
        }

        if (isset($config['editor']) && isset($config['editor']['widget']) && isset($config['editor']['class'])) {
                $container->setParameter('mosaic_widget.editor.widget_items', $config['editor']['widget']['items']);
        }

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
