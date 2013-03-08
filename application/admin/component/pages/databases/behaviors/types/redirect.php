<?php

use Nooku\Framework;

class ComPagesDatabaseBehaviorTypeRedirect extends ComPagesDatabaseBehaviorTypeAbstract
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
            $this->_type_title = JText::_('Redirect');
        }

        return $this->_type_title;
    }

    public function getTypeDescription()
    {
        if(!isset($this->_type_description)) {
            $this->_type_description = JText::_('Redirect');
        }

        return $this->_type_description;
    }

    protected function _setLinkBeforeSave(Framework\CommandContext $context)
    {
        if($this->link_type) {
            $this->link_type == 'id' ? $this->link_url = null : $this->link_id = null;
        }
    }

    protected function _beforeTableInsert(Framework\CommandContext $context)
    {
        $this->_setLinkBeforeSave($context);
    }

    protected function _beforeTableUpdate(Framework\CommandContext $context)
    {
        $this->_setLinkBeforeSave($context);
    }
}