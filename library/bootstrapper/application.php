<?php
/**
 * @package     Koowa_Bootstrapper
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Application Bootstrapper
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Bootstrapper
 */
class BootstrapperApplication extends BootstrapperChain
{
    /**
     * Constructor.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_directory = $config->directory;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Config $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'directory' => '',
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
        foreach (new \DirectoryIterator($this->_directory) as $dir)
        {
            //Only get the component directory names
            if ($dir->isDot() || !$dir->isDir() || !preg_match('/^[a-zA-Z]+/', $dir->getBasename())) {
                continue;
            }

            $bootstrapper = $this->getObject('com:'.$dir.'.bootstrapper');
            $this->enqueue($bootstrapper);
        }

        parent::bootstrap();
    }
}