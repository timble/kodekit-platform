<?php
/**
* @package      Koowa_Config
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Framework;

/**
 * Config Factory
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Config
 */
class ConfigFactory extends Object implements ServiceInstantiatable
{
    /**
     * Registered config file formats.
     *
     * @var array
     */
    protected $_formats;

    /**
     * Force creation of a singleton
     *
     * @param 	Config                  $config	  A Config object with configuration options
     * @param 	ServiceManagerInterface	$manager  A ServiceInterface object
     * @return  ConfigFactory
     */
    public static function getInstance(Config $config, ServiceManagerInterface $manager)
    {
        if (!$manager->has($config->service_identifier))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

    /**
     * Constructor
     *
     * @param Config $config An optional Config object with configuration options.
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        $this->_formats = $config->formats;
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional Config object with configuration options.
     * @return void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'formats' => array(
                'ini'  => 'ini',
                'json' => 'json',
                'xml'  => 'xml',
                'yaml' => 'yaml'
            )
        ));

        parent::_initialize($config);
    }

    /**
     * Get a registered config object.
     *
     * @param  string $name The format name
     * @throws \InvalidArgumentException    If the format isn't registered
     * @throws	\UnexpectedValueException	If the format object doesn't implement the ConfigFormattable
     * @return	ConfigFactory
     */
    public function getConfig($format)
    {
        $format = strtolower($format);

        if (!isset($this->_formats[$format])) {
            throw new \RuntimeException(sprintf('Unsupported config format: %s ', $format));
        }

        $format = $this->_formats[$format];

        if(!($format instanceof ConfigFormattable))
        {
            if(is_string($format) && strpos($format, '.') === false )
            {
                $identifier			= clone $this->getIdentifier();
                $identifier->path	= array('format');
                $identifier->name	= $format;
            }
            else $identifier = $this->getIdentifier($format);

            $format = $this->getService($identifier);

            if(!$format instanceof ConfigFormattable)
            {
                throw new \UnexpectedValueException(
                    'Format: '.get_class($format).' does not implement ConfigFormatInterface'
                );
            }

            $this->_formats[$format->name] = $format;
        }
        else $format = clone $format;

        return $format;
    }

    /**
     * Register config format
     *
     * @param string $format    The name of the format
     * @param mixed	$identifier An object that implements ServiceInterface, ServiceIdentifier object
     * 					        or valid identifier string
     * @return	ConfigFactory
     */
    public function registerFormat($format, $identifier)
    {
        $this->_formats[$format] = $identifier;
        return $this;
    }

    /**
     * Read a config from a file.
     *
     * @param  string  $filename
     * @return Config
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function fromFile($filename)
    {
        $pathinfo = pathinfo($filename);

        if (!isset($pathinfo['extension']))
        {
            throw new \RuntimeException(sprintf(
                'Filename "%s" is missing an extension and cannot be auto-detected', $filename
            ));
        }

        $config = $this->getConfig($pathinfo['extension'])->fromFile($filename);
        return $config;
    }

    /**
     * Writes a config to a file
     *
     * @param string $filename
     * @param Config $config
     * @return boolean TRUE on success. FALSE on failure
     * @throws \RuntimeException
     */
    public function toFile($filename, Config $config)
    {
        $pathinfo = pathinfo($filename);

        if (!isset($pathinfo['extension']))
        {
            throw new \RuntimeException(sprintf(
                'Filename "%s" is missing an extension and cannot be auto-detected', $filename
            ));
        }

        return $this->getConfig($pathinfo['extension'])->toFile($filename, $config);
    }
}