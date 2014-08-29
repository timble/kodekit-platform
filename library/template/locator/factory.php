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
 * Template Locator Factory
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Locator\Factory
 */
class TemplateLocatorFactory extends Object implements ObjectSingleton
{
    /**
     * Registered locators
     *
     * @var array
     */
    private $__locators;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Register the locators
        $locators = ObjectConfig::unbox($config->locators);

        foreach ($locators as $key => $value)
        {
            if (is_numeric($key)) {
                $this->registerLocator($value);
            } else {
                $this->registerLocator($key, $value);
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
            'locators' => array(
                'lib:template.locator.file',
                'lib:template.locator.component'
            ),
        ));
    }

    /**
     * Create a locator
     *
     * Note that only URLs delimited by "://"" are supported. ":" and ":/" while technically valid URLs, are not. If no
     * locator is registered for the specific scheme a exception will be thrown.
     *
     * @param  string $url  The template url
     * @param  array $config  An optional associative array of configuration options
     * @throws \InvalidArgumentException If the path is not valid
     * @throws \RuntimeException         If the locator isn't registered
     * @throws \UnexpectedValueException If the locator object doesn't implement the TemplateLocatorInterface
     * @return TemplateLocatorInterface
     */
    public function createLocator($url, array $config = array())
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);

        //If no scheme is specified fall back to file:// locator
        $name = !empty($scheme) ? $scheme : 'file';

        //If a windows drive letter is passed use file:// locator
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        {
            if(preg_match('#^[a-z]{1}$#i', $name)) {
                $name = 'file';
            }
        }

        //Locator not supported
        if(!in_array($name, $this->getLocators()))
        {
            throw new \RuntimeException(sprintf(
                'Unable to find the template locator "%s" - did you forget to register it ?', $name
            ));
        }

        //Create the locator
        $identifier = $this->getLocator($name);
        $locator    = $this->getObject($identifier, $config);

        if(!$locator instanceof TemplateLocatorInterface)
        {
            throw new \UnexpectedValueException(
                'Locator: '.get_class($locator).' does not implement TemplateLocatorInterface'
            );
        }

        return $locator;
    }

    /**
     * Register a locator
     *
     * Function prevents from registering the locator twice
     *
     * @param string $identifier A locator identifier string
     * @param  array $config  An optional associative array of configuration options
     * @throws \UnexpectedValueException
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function registerLocator($identifier, array $config = array())
    {
        $result = false;

        $identifier = $this->getIdentifier($identifier);
        $class      = $this->getObject('manager')->getClass($identifier);

        if(!$class || !array_key_exists(__NAMESPACE__.'\TemplateLocatorInterface', class_implements($class)))
        {
            throw new \UnexpectedValueException(
                'Locator: '.$identifier.' does not implement TemplateLocatorInterface'
            );
        }

        $name = $class::getName();

        if (!empty($name) && !$this->isRegistered($name))
        {
            $identifier->getConfig()->merge($config);
            $this->__locators[$name] = $identifier;
        }

        return $result;
    }

    /**
     * Unregister a locator
     *
     * @param string $identifier A locator object identifier string or locator name
     * @throws \UnexpectedValueException
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function unregisterLocator($identifier)
    {
        $result = false;

        if(strpos($identifier, '.') !== false )
        {
            $identifier = $this->getIdentifier($identifier);
            $class      = $this->getObject('manager')->getClass($identifier);

            if(!$class || !array_key_exists(__NAMESPACE__.'\TemplateLocatorInterface', class_implements($class)))
            {
                throw new \UnexpectedValueException(
                    'Locator: '.$identifier.' does not implement TemplateLocatorInterface'
                );
            }

            $name = $class::getName();

        }
        else $name = $identifier;

        if (!empty($name) && $this->isRegistered($name)) {
            unset($this->__locators[$name]);
        }

        return $result;
    }

    /**
     * Get a registered locator identifier
     *
     * @param string $name The locator name
     * @return string|false The locator identifier
     */
    public function getLocator($name)
    {
        $locator = false;

        if($this->isRegistered($name)) {
            $locator = $this->__locators[$name];
        }

        return $locator;
    }

    /**
     * Get a list of all the registered locators
     *
     * @return array
     */
    public function getLocators()
    {
        $result = array();
        if(is_array($this->__locators)) {
            $result = array_keys($this->__locators);
        }

        return $result;
    }

    /**
     * Check if the locator is registered
     *
     * @param string $identifier A locator object identifier string or locator name
     * @return bool TRUE if the locator is a registered, FALSE otherwise.
     */
    public function isRegistered($identifier)
    {
        if(strpos($identifier, '.') !== false )
        {
            $identifier = $this->getIdentifier($identifier);
            $class      = $this->getObject('manager')->getClass($identifier);

            if(!$class || !array_key_exists(__NAMESPACE__.'\TemplateLocatorInterface', class_implements($class)))
            {
                throw new \UnexpectedValueException(
                    'Locator: '.$identifier.' does not implement TemplateLocatorInterface'
                );
            }

            $name  = $class::getName();
        }
        else $name = $identifier;

        $result = in_array($name, $this->getLocators());
        return $result;
    }
}
