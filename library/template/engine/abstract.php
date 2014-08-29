<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Template Engine
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Abstract
 */
abstract class TemplateEngineAbstract extends TemplateAbstract implements TemplateEngineInterface
{
    /**
     * The engine file types
     *
     * @var string
     */
    protected static $_file_types = array();

    /**
     * Template object
     *
     * @var	TemplateInterface
     */
    private $__template;

    /**
     * Caching enabled
     *
     * @var bool
     */
    protected $_cache;

    /**
     * Cache path
     *
     * @var string
     */
    protected $_cache_path;

    /**
     * Constructor
     *
     * @param ObjectConfig $config   An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        // Set the template object
        $this->setTemplate($config->template);

        //Reset the stack
        $this->_stack = array();

        //Set caching
        $this->_cache        = $config->cache;
        $this->_cache_path   = $config->cache_path;
        $this->_cache_reload = $config->cache_reload;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'cache'        => false,
            'cache_path'   => '',
            'cache_reload' => true,
            'template'     => null,
            'functions'    => array(
                'object'    => array($this, 'getObject'),
                'translate' => array($this->getObject('translator'), 'translate'),
                'json'      => 'json_encode',
                'format'    => 'sprintf',
                'replace'   => 'strtr',
            ),
        ));

        parent::_initialize($config);
    }

    /**
     * Get the engine supported file types
     *
     * @return array
     */
    public static function getFileTypes()
    {
        return static::$_file_types;
    }

    /**
     * Get the template object
     *
     * @return TemplateInterface The template object
     */
    public function getTemplate()
    {
        return $this->__template;
    }

    /**
     * Set the template object
     *
     * @param  TemplateInterface $template The template object
     * @return TemplateFilterInterface $template The template object
     */
    public function setTemplate(TemplateInterface $template)
    {
        $this->__template = $template;
        return $this;
    }

    /**
     * Cache the template to a file
     *
     * Write the template content to a file cache. Requires cache to be enabled.
     *
     * @param  string $file    The file name
     * @param  string $content  The template content to cache
     * @throws \RuntimeException If the file path does not exist
     * @throws \RuntimeException If the file path is not writable
     * @throws \RuntimeException If template cannot be written to the cache
     * @return string|false The cached file path. FALSE if the file cannot be stored in the cache
     */
    public function cache($file, $content)
    {
        if($this->_cache)
        {
            $path = $this->_cache_path;

            if(!is_dir($path)) {
                throw new \RuntimeException(sprintf('The template cache path "%s" does not exist', $path));
            }

            if(!is_writable($path)) {
                throw new \RuntimeException(sprintf('The template cache path "%s" is not writable', $path));
            }

            $hash = crc32($file);
            $file = $path.'/template_'.$hash;

            if(!file_put_contents($file, $content)) {
                throw new \RuntimeException(sprintf('The template cannot be cached in "%s"', $file));
            }

            return $file;
        }

        return false;
    }

    /**
     * Check if a file exists in the cache
     *
     * @param string $file The file name
     * @return string|false The cache file path. FALSE if the file cannot be found in the cache
     */
    public function isCached($file)
    {
        $result = false;

        if($this->_cache)
        {
            $hash   = crc32($file);
            $cache  = $this->_cache_path.'/template_'.$hash;
            $result = is_file($cache) ? $cache : false;

            if($result && $this->_cache_reload && is_file($file))
            {
                if(filemtime($cache) < filemtime($file)) {
                    $result = false;
                }
            }
        }

        return $result;
    }
}
