<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Pagelink Typable Database Behavior
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseBehaviorTypePagelink extends DatabaseBehaviorTypeAbstract
{
    protected $_type_title;

    protected $_type_description;

    public static function getInstance(Library\Config $config, Library\ServiceManagerInterface $manager)
    {
        $instance = parent::getInstance($config, $manager);

        if(!$manager->has($config->service_identifier)) {
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

    public function getTypeTitle()
    {
        if(!isset($this->_type_title)) {
            $this->_type_title = \JText::_('Page Link');
        }

        return $this->_type_title;
    }

    public function getTypeDescription()
    {
        if(!isset($this->_type_description)) {
            $this->_type_description = \JText::_('Page Link');
        }

        return $this->_type_description;
    }
}