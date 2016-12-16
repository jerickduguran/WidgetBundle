<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\Widget; 

class WidgetManager 
{ 
    const WIDGET_SUPPORTED_FIELD_TYPES = array("text","textarea","select",'entity','radio','entity_with_dependent');
    
    protected $widgets;
    protected $router;
    protected $container;
    
    public function getWidgets()
    { 
        return $this->widgets;
    }
    
    public function addWidget($widget, $id, $widget_items = array())
    {
        if(!$widget instanceof WidgetInterface){
            throw new \Exception(sprintf("%s must be an instance of %s",$widget,"WidgetInterface"));
        }
        
        if(!in_array($id,array_keys($widget_items))){ 
            //throw new \Exception(sprintf("No configuration found for %s.",$id));
        }
        
		$configSettings = array();
		if ($widget_items && isset($widget_items[$id])) {
			$configSettings = $widget_items[$id]['settings'];
		}
		$widgetSettings = $widget->configureSettings();
		
		$settings = array_merge($widgetSettings, $configSettings);
		
        //do validation for settings
        $settings = $this->validateWidgetSettings($id, $settings);
         
        $widget->setName($widget->getName());
        $widget->setSettings($settings);  
        
        $this->widgets[$id] = $widget;
        
        return $this;
    }
    
    private function validateWidgetSettings($id, $settings = array())
    {
        if(!$settings){
           // throw new \Exception(sprintf("No settings found for %s.",$id));
        }
       
        return $this->validateFieldTypes($settings);
    }
    
    private function validateFieldTypes($settings)
    {   
        foreach($settings as $field => $attributes)
        {
            if(!isset($attributes['type'])){
                throw new \Exception(sprintf("Please define type for %s.",$field));
            }elseif(!in_array($attributes['type'],self::WIDGET_SUPPORTED_FIELD_TYPES)){
                throw new \Exception(sprintf("Unsupported field type %s. Suported Types are: %s.",$attributes['type'],"'".implode("', '",self::WIDGET_SUPPORTED_FIELD_TYPES) . "'"));
            }elseif(!isset($attributes['label'])){ 
                throw new \Exception(sprintf("Please define label for %s.",$field));
            }elseif($attributes['type'] == 'entity'  ){
                if(!isset($attributes['admin_code']) || !isset($attributes['admin_list_route'])){ 
                    throw new \Exception(sprintf("Please define admin_code and  admin_list_route for %s.",$field));
                } 	
                $params = array(); 
                if(isset($settings[$field]['admin_list_route_params'])){
                    $params = $settings[$field]['admin_list_route_params'];
                }	
                
                //add site parameter,if found
                $site = $this->container->get("request_stack")->getCurrentRequest()->get("site");
                
                if($site){
                    $params = array_merge($params,array('site' => $site,'hide_site' => true ));
                }  
                $settings[$field]['admin_list_route']= $this->router->generate($attributes['admin_list_route'],$params);
            } elseif($attributes['type'] == 'entity_with_dependent'  ){
                                 
                //add site parameter,if found
                $site = $this->container->get("request_stack")->getCurrentRequest()->get("site");
                foreach($attributes['dependency_map'] as $type => $attrs){
                    if(!isset($attrs['admin_code']) || !isset($attrs['admin_list_route'])){ 
                        throw new \Exception(sprintf("Please define admin_code and  admin_list_route for %s.",$type));
                    }
                     
                    $params = array(); 
                    if(isset($settings[$field]['admin_list_route_params'])){
                        $params = $settings[$field]['admin_list_route_params'];
                    }	
                    
                    if($site){
                        $params = array_merge($params,array('site' => $site,'hide_site' => true ));
                    } 

                    $settings[$field]['dependency_map'][$type]['admin_list_route']= $this->router->generate($attrs['admin_list_route'],$params);
                } 
            }
        }  
        
        return $settings; 
    } 
    
    public function setRouter($router)
    {
        $this->router = $router;
    }
    
    public function setContainer($container)
    { 
        $this->container = $container; 
        
        return $this;
    } 
}