<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Application Object Bootstrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Bootstrapper
 */
class Bootstrapper extends Library\ObjectBootstrapperComponent
{
    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_directory = $config->directory;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'directory'  => '',
            'aliases'    => array('translator' => 'com:application.translator'),
            'namespaces' => array('psr' => array(array('Symfony\Component\Yaml', JPATH_VENDOR . '/symfony/yaml')))
        ));

        parent::_initialize($config);
    }

    /**
     * Bootstrap the application
     *
     * @return void
     */
    public function bootstrap()
    {
        $chain = $this->getObject('lib:object.bootstrapper.chain');

        foreach ($this->getComponents($this->_directory) as $component)
        {
            if($bootstrapper = $this->getBootstrapper($component)) {
                $chain->addBootstrapper($bootstrapper);
            }
        }

        $chain->bootstrap();

        parent::bootstrap();
    }

    public function getComponents($directory)
    {
        $components = array();
        foreach (new \DirectoryIterator($directory) as $dir)
        {
            //Only get the component directory names
            if ($dir->isDot() || !$dir->isDir() || !preg_match('/^[a-zA-Z]+/', $dir->getBasename())) {
                continue;
            }

            $components[] = (string) $dir;
        }

        return $components;
    }

    public function getBootstrapper($name, $fallback = true)
    {
        $bootstrapper = null;

        $identifier = 'com:'.$name.'.bootstrapper';
        if($this->getObjectManager()->getClass($identifier, $fallback)) {
            $bootstrapper = $this->getObject($identifier);
        }

        return $bootstrapper;
    }

    public function getHandle()
    {
        //Prevent recursive bootstrapping
        return null;
    }
}