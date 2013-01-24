<?php
class ComPagesDatabaseBehaviorTypeRedirect extends KDatabaseBehaviorAbstract
{
    protected $_type_description;

    protected $_type_title;

    public function getTypeDescription()
    {
        if(!isset($this->_type_description)) {
            $this->_type_description = JText::_('Redirect');
        }

        return $this->_type_description;
    }

    public function getTypeTitle()
    {
        if(!isset($this->_type_title)) {
            $this->_type_title = JText::_('Redirect');
        }

        return $this->_type_title;
    }

    protected function _setLinkBeforeSave(KCommandContext $context)
    {
        $data = $context->data;
        if($data->link_type) {
            $data->link_type == 'id' ? $data->link_url = null : $data->link_id = null;
        }
    }

    protected function _beforeTableInsert(KCommandContext $context)
    {
        $this->_setLinkBeforeSave($context);
    }

    protected function _beforeTableUpdate(KCommandContext $context)
    {
        $this->_setLinkBeforeSave($context);
    }
}