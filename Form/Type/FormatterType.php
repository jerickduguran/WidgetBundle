<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\Form\Type;
   
use Symfony\Component\Form\FormView;  
use Symfony\Component\Form\FormInterface;

use Groupm\Mosaic\Core\FormatterBundle\Form\Type\FormatterType as BaseFormatterType;

class FormatterType extends BaseFormatterType
{   	
    protected $pluginManager;
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars['plugins'] = $this->pluginManager->getPlugins();
    }   
    
    public function setPluginManager($pluginManager)
    {
        $this->pluginManager = $pluginManager;
    }
}
