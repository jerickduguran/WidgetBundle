<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\Twig; 
   
use Symfony\Component\DomCrawler\Crawler;

class CoreExtension extends \Twig_Extension
{ 
	
    protected $container;
    protected $environment;

    public function __construct($container)
    {
            $this->container = $container;
    }
	
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
	
    public function getFunctions()
    {
        return array();
    }  
	
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('render_widgets', array($this, 'renderWidgets'),array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('json_decode', array($this, 'decodeJson')),
        );
    }
    
    public function getName()
    {
        return 'mosaic_widget_extension';
    } 
	
    public function decodeJson($data)
    {
        return json_decode($data,true);
    }
    
    public function renderWidgets($contents)
    { 
        if(!$contents){
              return "";
        }
        
        $widgetClass = $this->container->getParameter("mosaic_widget.editor.widget_class"); 
        $crawler     = new Crawler($contents); 

        try{ 
            $crawler->filter("widget.".$widgetClass)->each(function (Crawler $node, $i){  
               $attributes = $node->attr("data-attributes");
               $type       = $node->attr("data-type");
               $widget     = $this->getWidget($type);
               $widget->setAttributes($attributes);

               $templateContent = $widget->execute(); 
               
               $tmpDoc = new \DOMDocument();
               $tmpDoc->loadHTML($templateContent?$templateContent:"<span/>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD); 
               $node->getNode(0)->parentNode->replaceChild($node->getNode(0)->ownerDocument->importNode($tmpDoc->documentElement,true), $node->getNode(0));
           }); 
        }
        catch(Exception $e){
                // make sure to throw exception on debug mode.
                if ($this->environment->isDebug()) {
                        throw $e;
                }
        } 
		
		if($crawler->count() < 1){
			return;
		}
		
        //Remove extra body tag
        preg_match("/<body[^>]*>(.*?)<\/body>/is", $crawler->html(), $matches);
        
        return $matches[1]; 
    }
    
    private function getWidget($type)
    {
        $widget = $this->container->get($type);
        return $widget; 
    }  	 
}
