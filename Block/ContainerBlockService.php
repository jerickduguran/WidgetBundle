<?php
 
namespace Groupm\Mosaic\Bundle\WidgetBundle\Block;

use Sonata\PageBundle\Block\ContainerBlockService as BaseContainerBlockService; 
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Render children pages.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class ContainerBlockService extends BaseContainerBlockService
{
    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    { 
        $resolver->setDefaults(array(
            'code'        => '',
            'layout'      => '{{ CONTENT }}',
            'class'       => '',
            'template'    => 'MosaicWidgetBundle:Block:block_container.html.twig',
        ));
    }
}
