<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\Widget;

use Groupm\Mosaic\Bundle\WidgetBundle\Widget\AbstractWidget;
  
class PostLinkWidget extends AbstractWidget
{   
    public function getTemplate()
	{
        return "MosaicWidgetBundle:Widget:post_link.html.twig";
    } 
    
    public function execute()
    {  
        $postId = isset($this->attributes['post_id']) ? $this->attributes['post_id'] : 0; 
        $post   = $this->container->get("sonata.news.manager.post")->findOneBy(array("id" => $postId));     
          
        return $this->environment->render($this->getTemplate(),array('post' => $post, 'attributes' => $this->attributes));
    }
	
	public function configureSettings()
	{
            return array(
                'post_id' => array(
                        'label'                   => 'Post ID',
                        'type'                    => 'entity',
                        'admin_code'              => 'sonata.news.admin.post',
                        'admin_list_route'        => 'admin_app_news_post_list',
                        'admin_list_route_params' => array("collection" => "article",
                                                           "hide_collection" => false))
            );
	}
	
	public function getName()
	{
            return 'Post Link';
	}

}