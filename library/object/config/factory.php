<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Config Factory
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object
 */
class ObjectConfigFactory extends Object implements ObjectSingleton
{
    /**
     * Registered config file formats.
     *
     * @var array
     */
    protected $_formats;

    /**
     * Constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_formats = $config->formats;
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config	An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'formats' => array(
                'ini'  => 'Nooku\Library\ObjectConfigIni',
                'json' => 'Nooku\Library\ObjectConfigJson',
                'xml'  => 'Nooku\Library\ObjectConfigXml',
                'yaml' => 'Nooku\Library\ObjectConfigYaml'
            )
        ));

        parent::_initialize($config);
    }

    /**
     * Get a registered config object.
     *
     * @param  string $format The format name
     * @param   array|ObjectConfig $options An associative array of configuration options or a ObjectConfig instance.
     * @throws \InvalidArgumentException    If the format isn't registered
     * @throws \UnexpectedValueException	If the format object doesn't implement the ObjectConfigSerializable
     * @return ObjectConfigInterface
     */
    public function createFormat($format, $options = array())
    {
        $format = strtolower($format);

        if (!isset($this->_formats[$format])) {
            throw new \RuntimeException(sprintf('Unsupported config format: %s ', $format));
        }

        $format = $this->_formats[$format];

        if(!($format instanceof ObjectConfigSerializable))
        {
            $format = new $format($options);

            if(!$format instanceof ObjectConfigSerializable)
            {
                throw new \UnexpectedValueException(
                    'Format: '.get_class($format).' does not implement ObjectConfigSerializable Interface'
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
     * @param mixed	$identifier An object that implements ObjectInterface, ObjectIdentifier object
     * 					        or valid identifier string
     * @return	ObjectConfigFactory
     * throws \InvalidArgumentException If the class does not exist.
     */
    public function registerFormat($format, $class)
    {
        if(!class_exists($class, true)) {
            throw new \InvalidArgumentException('Class : '.$class.' cannot does not exist.');
        }

        $this->_formats[$format] = $class;
        return $this;
    }

    /**
     * Read a config from a string
     *
     * @param  string  $format
     * @param  string  $config
     * @return ObjectConfigInterface
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function fromString($format, $config)
    {
        $config = $this->createFormat($format)->fromString($config);
        return $config;
    }

    /**
     * Read a config from a file.
     *
     * @param  string  $filename
     * @return ObjectConfigInterface
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

        $config = $this->createFormat($pathinfo['extension'])->fromFile($filename);
        return $config;
    }

    /**
     * Writes a config to a file
     *
     * @param string $filename
     * @param ObjectConfigInterface $config
     * @return boolean TRUE on success. FALSE on failure
     * @throws \RuntimeException
     */
    public function toFile($filename, ObjectConfigInterface $config)
    {
        $pathinfo = pathinfo($filename);

        if (!isset($pathinfo['extension']))
        {
            throw new \RuntimeException(sprintf(
                'Filename "%s" is missing an extension and cannot be auto-detected', $filename
            ));
        }

        return $this->createFormat($pathinfo['extension'])->toFile($filename, $config);
    }
}