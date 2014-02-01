<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Object Locator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
abstract class ObjectLocatorAbstract extends Object implements ObjectLocatorInterface
{
    /**
     * The class prefix sequence in FIFO order
     *
     * @var array
     */
    protected $_sequence = array();

    /**
     * The class loader
     *
     * @var ClassLoaderInterface
     */
    private $__loader;

    /**
     * The locator type
     *
     * @var string
     */
    protected $_type = '';

    /**
     * Constructor.
     *
     * @param ObjectConfig $config  An optional KObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_sequence = ObjectConfig::unbox($config->sequence);

        //Set the class loader
        $this->setClassLoader($config->class_loader);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional KObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'sequence'      => array(),
            'class_loader'  => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Returns a fully qualified class name for a given identifier.
     *
     * @param ObjectIdentifier $identifier An identifier object
     * @param bool  $fallback   Use the fallbacks to locate the identifier
     * @return string|false  Return the class name on success, returns FALSE on failure
     */
    public function locate(ObjectIdentifier $identifier, $fallback = true)
    {
        $package = ucfirst($identifier->package);
        $path    = StringInflector::camelize(implode('_', $identifier->path));
        $file    = ucfirst($identifier->name);
        $class   = $path.$file;

        $info = array(
            'class'   => $class,
            'package' => $package,
            'path'    => $path,
            'file'    => $file
        );

        return $this->find($info, $identifier->domain, $fallback);
    }

    /**
     * Find a class
     *
     * @param array  $info      The class information
     * @param string $basepath  The basepath name
     * @param bool   $fallback  If TRUE use the fallback sequence
     * @return bool|mixed
     */
    public function find(array $info, $basepath = null, $fallback = true)
    {
        $result = false;

        //Set the basepath
        if(!empty($basepath)) {
            $this->getClassLoader()->setBasepath($basepath);
        }

        //Find the class
        foreach($this->_sequence as $template)
        {
            $class= str_replace(
                array('<Package>'     ,'<Path>'      ,'<File>'      , '<Class>'),
                array($info['package'], $info['path'], $info['file'], $info['class']),
                $template
            );

            if(class_exists($class))
            {
                $result = $class;
                break;
            }

            if(!$fallback) {
                break;
            }
        }

        return $result;
    }

    /**
     * Get the type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get the locator fallback sequence
     *
     * @return array
     */
    public function getSequence()
    {
        return $this->_sequence;
    }

    /**
     * Get the class loader
     *
     * @return ClassLoaderInterface
     */
    public function getClassLoader()
    {
        return $this->__loader;
    }

    /**
     * Set the class loader
     *
     * @param  ClassLoaderInterface $loader
     * @return ObjectLocatorInterface
     */
    public function setClassLoader(ClassLoaderInterface $loader)
    {
        $this->__loader = $loader;
        return $this;
    }
}