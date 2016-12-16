<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class EditorController extends Controller 
{
    public function pluginAction()
    {  
        $widget_manager = $this->container->get("mosaic_widget.editor.manager.widget");
        $widgets        = $widget_manager->getWidgets();   
        $editor_params  = $this->getEditorParams();
	$uniqueId       = uniqid("mosaicwidget");
        $plugin_js      = $this->renderView("MosaicWidgetBundle:Editor:plugin.html.twig",array('widgets' => $widgets,'params' => $editor_params, 'uniqueId' => $uniqueId));
        return new Response($plugin_js, Response::HTTP_OK, array( 'Content-Type' => 'application/javascript'));   
    }
    
    public function langAction($lang = 'en')
    {  
        $lang_js = $this->renderView("MosaicWidgetBundle:Editor\lang:{$lang}.html.twig");  
        
        return new Response( $lang_js, Response::HTTP_OK, array( 'Content-Type' => 'application/javascript'));   
    }
    
    public function iconAction($name)
    {   
        $filepath    = $this->get('kernel')->getRootDir() . '/../web/'."bundles/mosaicwidget/editor/icons/".$name; 
        $response    = new Response();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $name);
        
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'image/png');
        $response->setContent(file_get_contents($filepath));

        return $response;
    }
    
    public function styleAction($name)
    {     
        $editor_params  = $this->getEditorParams();
        $css            = $this->renderView("MosaicWidgetBundle:Editor\style:{$name}.html.twig",array('params' => $editor_params)); 
        
        return new Response( $css, Response::HTTP_OK,  array(  'Content-Type'  => 'text/css' ));   
    }
    
    protected function getEditorParams()
    {
        $params = array('class' => $this->container->getParameter("mosaic_widget.editor.widget_class"),
                        'name'  => $this->container->getParameter("mosaic_widget.editor.widget_name"));
        
        return $params;
    }
}
