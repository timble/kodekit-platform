<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Url Database Behavior Interface
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseBehaviorTypeUrl extends DatabaseBehaviorTypeAbstract
{
    protected $_title;

    protected $_description;

    public static function getInstance(Library\ObjectConfig $config, Library\ObjectManagerInterface $manager)
    {
        $instance = parent::getInstance($config, $manager);

        if (!$manager->isRegistered($config->object_identifier)) {
            $manager->setObject($config->object_identifier, $instance);
        }

        return $manager->getObject($config->object_identifier);
    }

    public function getTitle()
    {
        if (!isset($this->_title)) {
            $this->_title = \JText::_('External Link');
        }

        return $this->_title;
    }

    public function getDescription()
    {
        if (!isset($this->_description)) {
            $this->_description = \JText::_('External Link');
        }

        return $this->_description;
    }
}