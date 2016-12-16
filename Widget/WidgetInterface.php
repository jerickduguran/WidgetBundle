<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\Widget;

interface WidgetInterface
{ 
    public function getName(); 
    public function getSettings();    
    public function execute();      
}