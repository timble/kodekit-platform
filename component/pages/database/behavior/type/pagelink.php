<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Pagelink Typable Database Behavior
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseBehaviorTypePagelink extends DatabaseBehaviorTypeAbstract
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
        if(!isset($this->_type_title)) {
            $this->_type_title = $this->getObject('translator')->translate('Page Link');
        }

        return $this->_title;
    }

    public function getDescription()
    {
        if(!isset($this->_type_description)) {
            $this->_type_description = $this->getObject('translator')->translate('Page Link');
        }

        return $this->_description;
    }
}
