<?php

namespace Groupm\Mosaic\Bundle\WidgetBundle\Widget;

use Groupm\Mosaic\Bundle\WidgetBundle\Widget\AbstractWidget;
  
class PostLabelWidget extends AbstractWidget
{   
    public function getTemplate()
	{
        return "MosaicWidgetBundle:Widget:post_label.html.twig";
    } 
    
    public function execute()
    {  
        $post = isset($this->attributes['post']) ? $this->attributes['post'] : 0;
		
        $post = $this->container->get("sonata.news.manager.post")->findOneBy(array("id" => $post));     
		
		if (empty($this->attributes['format'])) {
			$this->attributes['format'] = '%s';
		}
        
        return $this->environment->render($this->getTemplate(),array('post' => $post, 'attributes' => $this->attributes));
    }
	
	public function configureSettings()
	{
		return array(
			'post_id' => array(
				'label'            => 'Post',
				'type'             => 'entity',
				'admin_code'       => 'sonata.news.admin.post',
				'admin_list_route' => 'admin_app_news_post_list'),
			'format'  => array(
				'label'            => 'Format',
				'type'             => 'text'
			)
		);
	}
	
	public function getName()
	{
		return 'Post Label';
	}

}