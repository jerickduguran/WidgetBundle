<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\Widget;

use Groupm\Mosaic\Bundle\WidgetBundle\Widget\AbstractWidget;
  
class PostSetWidget extends AbstractWidget
{   
    public function getTemplate()
	{
        return "MosaicWidgetBundle:Widget:postset.html.twig";
    } 
    
    public function execute()
    {  
        $postsetId = isset($this->attributes['postset_id']) ? $this->attributes['postset_id'] : 0;	
        $postSet   = $this->container->get("mosaic.news.manager.post_sets")->findOneBy(array("id" => $postsetId));     
	  
        return $this->environment->render($this->getTemplate(),array('postSet' => $postSet, 'attributes' => $this->attributes));
    }
	
    public function configureSettings()
    {
        return array( 
            'postset_id' => array(
                    'label'                   => 'Post Set ID',
                    'type'                    => 'entity',
                    'admin_code'              => 'mosaic.news.admin.post_sets',
                    'admin_list_route'        => 'admin_app_news_postsets_list',
                    'admin_list_route_params' => array("collection" => "default",
                                                       "hide_collection" => false)), 
            'description' => array(
                    'label'                   => 'Description',
                    'type'                    => 'textarea'),
        );
    }

    public function getName()
    {
        return 'Post Sets';
    }

}