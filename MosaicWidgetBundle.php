<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MosaicWidgetBundle extends Bundle
{ 
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {  
        $container->addCompilerPass(new DependencyInjection\Compiler\WidgetTagCompilerPass());
		$container->addCompilerPass(new DependencyInjection\Compiler\OverrideFormatterCompilerPass());
    }
    
}
