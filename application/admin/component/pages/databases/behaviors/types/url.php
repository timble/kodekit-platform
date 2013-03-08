<?php

use Nooku\Framework;

class ComPagesDatabaseBehaviorTypeUrl extends ComPagesDatabaseBehaviorTypeAbstract
{
    protected $_type_title;

    protected $_type_description;

    public static function getInstance(Framework\Config $config, Framework\ServiceManagerInterface $manager)
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
            $this->_type_title = JText::_('External Link');
        }

        return $this->_type_title;
    }

    public function getTypeDescription()
    {
        if(!isset($this->_type_description)) {
            $this->_type_description = JText::_('External Link');
        }

        return $this->_type_description;
    }
}