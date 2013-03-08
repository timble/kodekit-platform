<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Dynamic Module Html View Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesModuleDynamicHtml extends ComDefaultModuleDefaultHtml implements Framework\ServiceInstantiatable
{
    public static function getInstance(Framework\Config $config, Framework\ServiceManagerInterface $manager)
    {
        if (!$manager->has($config->service_identifier))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

    public function render()
    {
        //Dynamically attach the chrome filter
        if(!empty($this->module->chrome)) {
            $this->getTemplate()->attachFilter('chrome', array('styles' => $this->module->chrome));
        }

        $this->_content = $this->getTemplate()
            ->loadString($this->_content, $this->_data)
            ->render();

        return $this->_content;
    }
}