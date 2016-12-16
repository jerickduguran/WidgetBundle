<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\Widget;

use Groupm\Mosaic\Bundle\WidgetBundle\Widget\WidgetInterface;

abstract class AbstractWidget implements WidgetInterface
{ 
    protected $name;  
    protected $settings;
    protected $attributes;   
    protected $environment;      
    protected $container;
    
    public function __construct($container)
    {
         $this->container   = $container;
         $this->environment = $container->get('twig');
    }
    
    public function execute()
    { 
        return $this->environment->render($this->getTemplate(),array('attributes' => $this->attributes));
    }
    
    public function setName($name)
    {
        $this->name  = ucwords(str_replace("_"," ",$name)); 
        
        return $this;
    }   
    
    public function getName()
    { 
        return $this->name;
    }
	
	public function configureSettings()
	{
		return $this->settings;
	}
    
    public function setSettings($settings)
    {
        $this->settings = $settings;
        
        return $this;
    }
    
    public function getSettings()
    {
        return $this->settings;
    }
     
    public function setAttributes($attributes)
    {
        $this->attributes = $this->normalizeAttributes($attributes);
        
        return $this;
    }	
    
    protected function normalizeAttributes($attributes)
    { 
        if($attributes){
            $attributeGroups = explode(",",$attributes);       
            if($attributeGroups){ 
                $normalizedAttributess = array();
                foreach($attributeGroups as $attributeGroup){
					if(strpos($attributeGroup,"<::>")){
						$attribute         = explode("<::>",$attributeGroup);
					}else{
						$attribute         = explode(":",$attributeGroup);
					}
                    $normalizedAttributess[$attribute[0]] = $attribute[1];
                } 
                return $normalizedAttributess;
            }
        }
        
        return null; 
    }
    
    abstract public function getTemplate();
}