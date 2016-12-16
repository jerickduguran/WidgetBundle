<?php
  
namespace Groupm\Mosaic\Bundle\WidgetBundle\Helper;
  
use Ivory\CKEditorBundle\Templating\CKEditorHelper as BaseCKEditorHelper;
 
class CKEditorHelper extends BaseCKEditorHelper
{    
    public function renderWidget($id, array $config, $inline = false, $inputSync = false)
    { 
        $allConfig  = $this->fixConfigMosaicWidget($config);
        $replace    = parent::renderWidget($id, $allConfig, $inline, $inputSync);
        return $replace;
    } 
    
    protected function fixConfigMosaicWidget(array $config)
    {    
        $unique_id                    = uniqid("mosaicwidget");
        $config['mosaicwidget']['id'] = $unique_id; 
        return $config;
    }
    
}
