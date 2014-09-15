<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Template Engine Factory
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Engine\Factory
 */
class TemplateEngineFactory extends Object implements ObjectSingleton
{
    /**
     * Registered engines
     *
     * @var array
     */
    private $__engines;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config Configuration options
     */
    public function __construct( ObjectConfig $config)
    {
        parent::__construct($config);

        //Register the engines
        $engines = ObjectConfig::unbox($config->engines);

        foreach ($engines as $key => $value)
        {
            if (is_numeric($key)) {
                $this->registerEngine($value);
            } else {
                $this->registerEngine($key, $value);
            }
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config Configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'debug'      => false,
            'cache'      => false,
            'cache_path' => '',
            'engines'    => array(
                'lib:template.engine.nooku'
            ),
        ));
    }

    /**
     * Create an engine
     *
     * Note that only paths ending with '.[type]' are supported. If the url is not a path we will assume the url is
     * the type. If no engine is registered for the specific file type a exception will be thrown.
     *
     * @param  string $url    The template url or engine type
     * @param  array $config  An optional associative array of configuration options
     * @param  TemplateInterface $template
     *
     * @throws \InvalidArgumentException If the path is not valid
     * @throws \RuntimeException         If the engine isn't registered
     * @throws \UnexpectedValueException If the engine object doesn't implement the TemplateEngineInterface
     * @return TemplateEngineInterface
     */
    public function createEngine($url, array $config = array())
    {
        //Find the file type
        if(!$type = pathinfo($url, PATHINFO_EXTENSION)) {
            $type = $url;
        }

        //Engine not supported
        if(!in_array($type, $this->getFileTypes()))
        {
            throw new \RuntimeException(sprintf(
                'Unable to find a template engine for the "%s" file format - did you forget to register it ?', $type
            ));
        }

        //Create the engine
        $identifier = $this->getEngine($type);
        $engine     = $this->getObject($identifier, $config);

        if(!$engine instanceof TemplateEngineInterface)
        {
            throw new \UnexpectedValueException(
                'Engine: '.get_class($engine).' does not implement TemplateEngineInterface'
            );
        }

        return $engine;
    }

    /**
     * Register an engine
     *
     * Function prevents from registering the engine twice
     *
     * @param string $identifier A engine identifier string
     * @param  array $config  An optional associative array of configuration options
     * @throws \UnexpectedValueException
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function registerEngine($identifier, array $config = array())
    {
        $result = false;

        $identifier = $this->getIdentifier($identifier);
        $class      = $this->getObject('manager')->getClass($identifier);

        if(!$class || !array_key_exists(__NAMESPACE__.'\TemplateEngineInterface', class_implements($class)))
        {
            throw new \UnexpectedValueException(
                'Engine: '.$identifier.' does not implement TemplateEngineInterface'
            );
        }

        $types = $class::getFileTypes();

        if (!empty($types))
        {
            foreach($types as $type)
            {
                if(!$this->isRegistered($type))
                {
                    $identifier->getConfig()->merge($config)->append(array(
                        'debug'      => $this->getConfig()->debug,
                        'cache'      => $this->getConfig()->cache,
                        'cache_path' => $this->getConfig()->cache_path
                    ));

                    $this->__engines[$type] = $identifier;
                }
            }
        }

        return $result;
    }

    /**
     * Unregister an engine
     *
     * @param string $identifier A engine object identifier string or file type
     * @throws \UnexpectedValueException
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function unregisterEngine($identifier)
    {
        $result = false;

        if(strpos($identifier, '.') !== false )
        {
            $identifier = $this->getIdentifier($identifier);
            $class      = $this->getObject('manager')->getClass($identifier);

            if(!$class || !array_key_exists(__NAMESPACE__.'\TemplateEngineInterface', class_implements($class)))
            {
                throw new \UnexpectedValueException(
                    'Engine: '.$identifier.' does not implement TemplateEngineInterface'
                );
            }

            $types = $class::getFileTypes();

        }
        else $types = (array) $identifier;

        if (!empty($types))
        {
            foreach($types as $type)
            {
                if($this->isRegistered($type)) {
                    $this->__engines[$type] = $identifier;
                }
            }
        }

        return $result;
    }

    /**
     * Get a registered engine identifier
     *
     * @param string $type The file type
     * @return string|false The engine identifier
     */
    public function getEngine($type)
    {
        $engine = false;

        if(isset($this->__engines[$type])) {
            $engine = $this->__engines[$type];
        }

        return $engine;
    }

    /**
     * Get a list of all the registered file types
     *
     * @return array
     */
    public function getFileTypes()
    {
        $result = array();
        if(is_array($this->__engines)) {
            $result = array_keys($this->__engines);
        }

        return $result;
    }

    /**
     * Check if the engine is registered
     *
     * @param string $identifier A engine object identifier string or a file type
     * @return bool TRUE if the engine is a registered, FALSE otherwise.
     */
    public function isRegistered($identifier)
    {
        if(strpos($identifier, '.') !== false )
        {
            $identifier = $this->getIdentifier($identifier);
            $class      = $this->getObject('manager')->getClass($identifier);

            if(!$class || !array_key_exists(__NAMESPACE__.'\TemplateEngineInterface', class_implements($class)))
            {
                throw new \UnexpectedValueException(
                    'Engine: '.$identifier.' does not implement TemplateEngineInterface'
                );
            }

            $types  = $class::getFileTypes();
        }
        else $types = (array) $identifier;

        $result = in_array($types, $this->getFileTypes());
        return $result;
    }
}
