<?php
class ComPagesDatabaseBehaviorTypePagelink extends KDatabaseBehaviorAbstract
{
    protected $_type_description;

    protected $_type_title;

    public function getTypeDescription()
    {
        if(!isset($this->_type_description)) {
            $this->_type_description = JText::_('Page Link');
        }

        return $this->_type_description;
    }

    public function getTypeTitle()
    {
        if(!isset($this->_type_title)) {
            $this->_type_title = JText::_('Page Link');
        }

        return $this->_type_title;
    }
}