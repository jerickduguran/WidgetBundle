<?php
  
namespace Groupm\Mosaic\Bundle\WidgetBundle\Helper;
 

use Ivory\JsonBuilder\JsonBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface; 
use Ivory\CKEditorBundle\Renderer\CKEditorRendererInterface as BaseCKEditorRendererInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class CKEditorRenderer implements BaseCKEditorRendererInterface 
{ 
      /** @var \Ivory\JsonBuilder\JsonBuilder */
    protected $jsonBuilder;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $container;
    
    protected $requestStack;

    /**
     * Creates a CKEditor renderer.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The container.
     */
    public function __construct(ContainerInterface $container)
    { 
        $this->jsonBuilder = new JsonBuilder();
        $this->container = $container;
    }
    
    public function setRequestStack($requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function renderBasePath($basePath)
    {
        return $this->fixPath($this->fixUrl($basePath));
    }

    /**
     * {@inheritdoc}
     */
    public function renderJsPath($jsPath)
    {
        return $this->fixUrl($jsPath);
    }

    /**
     * {@inheritdoc}
     */
    public function renderWidget($id, array $config, array $options = array())
    {
        $config = $this->fixConfigLanguage($config);
        $config = $this->fixConfigContentsCss($config);
        $config = $this->fixConfigFilebrowsers(
            $config,
            isset($options['filebrowsers']) ? $options['filebrowsers'] : array()
        );

        $this->jsonBuilder
            ->reset()
            ->setValues($config);

        $this->fixConfigEscapedValues($config);

        $autoInline = isset($options['auto_inline']) && !$options['auto_inline']
            ? 'CKEDITOR.disableAutoInline = true;'.PHP_EOL
            : null;

        $widget = sprintf(
            'CKEDITOR.%s("%s", %s);',
            isset($options['inline']) && $options['inline'] ? 'inline' : 'replace',
            $id,
            $this->fixConfigConstants($this->jsonBuilder->build())
        );

        if (isset($options['input_sync']) && $options['input_sync']) {
            $variable = 'ivory_ckeditor_'.$id;
            $widget = 'var '.$variable.' = '.$widget.PHP_EOL;

            return $autoInline.$widget.$variable.'.on(\'change\', function() { '.$variable.'.updateElement(); });';
        }

        return $autoInline.$widget;
    }

    /**
     * {@inheritdoc}
     */
    public function renderDestroy($id)
    {
        return sprintf('if (CKEDITOR.instances["%s"]) { delete CKEDITOR.instances["%s"]; }', $id, $id);
    } 
    
    /**
     * {@inheritdoc}
     */
    public function renderStylesSet($name, array $stylesSet)
    {
        $this->jsonBuilder
            ->reset()
            ->setValues($stylesSet);

        return sprintf(
            'if (CKEDITOR.stylesSet.get("%s") === null) { CKEDITOR.stylesSet.add("%s", %s); }',
            $name,
            $name,
            $this->jsonBuilder->build()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function renderTemplate($name, array $template)
    {
        if (isset($template['imagesPath'])) {
            $template['imagesPath'] = $this->fixPath($this->fixUrl($template['imagesPath']));
        }

        if (isset($template['templates'])) {
            foreach ($template['templates'] as &$rawTemplate) {
                if (isset($rawTemplate['template'])) {
                    $rawTemplate['html'] = $this->getTemplating()->render(
                        $rawTemplate['template'],
                        isset($rawTemplate['template_parameters']) ? $rawTemplate['template_parameters'] : array()
                    );
                }

                unset($rawTemplate['template']);
                unset($rawTemplate['template_parameters']);
            }
        }

        $this->jsonBuilder
            ->reset()
            ->setValues($template);

        return sprintf('CKEDITOR.addTemplates("%s", %s);', $name, $this->jsonBuilder->build());
    }

    
    /**
     * {@inheritdoc}
     */
    public function renderPlugin($name, array $plugin)
    {  
        $siteId = $this->requestStack->getCurrentRequest()->get("site");
        $params = "";
        if($siteId){
            $params = "?site=".$siteId;
        }
        return sprintf(
            'CKEDITOR.plugins.addExternal("%s", "%s", "%s");',
            $name,
            $this->fixPath($this->fixUrl($plugin['path'])),
            $plugin['filename'].$params
        );
    } 
    /**
     * Fixes the config language.
     *
     * @param array $config The config.
     *
     * @return array The fixed config.
     */
    protected function fixConfigLanguage(array $config)
    {
        if (!isset($config['language']) && ($language = $this->getLanguage()) !== null) {
            $config['language'] = $language;
        }

        if (isset($config['language'])) {
            $config['language'] = strtolower(str_replace('_', '-', $config['language']));
        }

        return $config;
    }

    /**
     * Fixes the config contents css.
     *
     * @param array $config The config.
     *
     * @return array The fixed config.
     */
    protected function fixConfigContentsCss(array $config)
    {
        if (isset($config['contentsCss'])) {
            $cssContents = (array) $config['contentsCss'];

            $config['contentsCss'] = array();
            foreach ($cssContents as $cssContent) {
                $config['contentsCss'][] = $this->fixPath($this->fixUrl($cssContent));
            }
        }

        return $config;
    }

    /**
     * Fixes the config filebrowsers.
     *
     * @param array $config       The config.
     * @param array $filebrowsers The filebrowsers.
     *
     * @return array The fixed config.
     */
    protected function fixConfigFilebrowsers(array $config, array $filebrowsers)
    {
        $filebrowsers = array_unique(array_merge(array(
            'Browse',
            'FlashBrowse',
            'ImageBrowse',
            'ImageBrowseLink',
            'Upload',
            'FlashUpload',
            'ImageUpload',
        ), $filebrowsers));

        foreach ($filebrowsers as $filebrowser) {
            $fileBrowserKey = 'filebrowser'.$filebrowser;
            $handler = $fileBrowserKey.'Handler';
            $url = $fileBrowserKey.'Url';
            $route = $fileBrowserKey.'Route';
            $routeParameters = $fileBrowserKey.'RouteParameters';
            $routeAbsolute = $fileBrowserKey.'RouteAbsolute';

            if (isset($config[$handler])) {
                $config[$url] = $config[$handler]($this->getRouter());
            } elseif (isset($config[$route])) {
                $config[$url] = $this->getRouter()->generate(
                    $config[$route],
                    isset($config[$routeParameters]) ? $config[$routeParameters] : array(),
                    isset($config[$routeAbsolute])
                        ? $config[$routeAbsolute]
                        : (
                            defined('Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_PATH')
                                ? UrlGeneratorInterface::ABSOLUTE_PATH
                                : false
                        )
                );
            }

            unset($config[$handler]);
            unset($config[$route]);
            unset($config[$routeParameters]);
            unset($config[$routeAbsolute]);
        }

        return $config;
    }

    /**
     * Fixes the config escaped values and sets them on the json builder.
     *
     * @param array $config The config.
     */
    protected function fixConfigEscapedValues(array $config)
    {
        if (isset($config['protectedSource'])) {
            foreach ($config['protectedSource'] as $key => $value) {
                $this->jsonBuilder->setValue(sprintf('[protectedSource][%s]', $key), $value, false);
            }
        }

        $escapedValueKeys = array(
            'stylesheetParser_skipSelectors',
            'stylesheetParser_validSelectors',
        );

        foreach ($escapedValueKeys as $escapedValueKey) {
            if (isset($config[$escapedValueKey])) {
                $this->jsonBuilder->setValue(sprintf('[%s]', $escapedValueKey), $config[$escapedValueKey], false);
            }
        }
    }

    /**
     * Fixes the config constants.
     *
     * @param string $json The json config.
     *
     * @return string The fixed config.
     */
    protected function fixConfigConstants($json)
    {
        return preg_replace('/"(CKEDITOR\.[A-Z_]+)"/', '$1', $json);
    }

    /**
     * Fixes a path.
     *
     * @param string $path The path.
     *
     * @return string The fixed path.
     */
    protected function fixPath($path)
    {
        if (($position = strpos($path, '?')) !== false) {
            return substr($path, 0, $position);
        }

        return $path;
    }

    /**
     * Fixes an url.
     *
     * @param string $url The url.
     *
     * @return string The fixed url.
     */
    protected function fixUrl($url)
    {
        $assetsHelper = $this->getAssetsHelper();

        if ($assetsHelper !== null) {
            $url = $assetsHelper->getUrl($url);
        }

        return $url;
    }

    /**
     * Gets the locale.
     *
     * @return string|null The locale.
     */
    protected function getLanguage()
    {
        if (($request = $this->getRequest()) !== null) {
            return $request->getLocale();
        }

        if ($this->container->hasParameter($parameter = 'locale')) {
            return $this->container->getParameter($parameter);
        }
    }

    /**
     * Gets the request.
     *
     * @return \Symfony\Component\HttpFoundation\Request|null The request.
     */
    protected function getRequest()
    {
        if ($this->container->has($service = 'request_stack')) {
            return $this->container->get($service)->getMasterRequest();
        }

        if ($this->container->has($service = 'request')) {
            return $this->container->get($service);
        }
    }

    /**
     * Gets the templating engine.
     *
     * @return \Symfony\Component\Templating\EngineInterface|\Twig_Environment The templating engine.
     */
    protected function getTemplating()
    {
        if ($this->container->has($templating = 'templating')) {
            return $this->container->get($templating);
        }

        return $this->container->get('twig');
    }

    /**
     * Gets the assets helper.
     *
     * @return \Symfony\Component\Asset\Packages|\Symfony\Component\Templating\Helper\CoreAssetsHelper|null The assets helper.
     */
    protected function getAssetsHelper()
    {
        return $this->container->get('assets.packages', ContainerInterface::NULL_ON_INVALID_REFERENCE);
    }

    /**
     * Gets the router.
     *
     * @return \Symfony\Component\Routing\RouterInterface The router.
     */
    protected function getRouter()
    {
        return $this->container->get('router');
    } 
}
