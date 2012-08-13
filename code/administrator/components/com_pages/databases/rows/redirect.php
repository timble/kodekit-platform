<?php
class ComPagesDatabaseRowRedirect extends ComPagesDatabaseRowPage
{
    public function save()
    {
        if($this->link_type) {
            $this->link_type == 'id' ? $this->link_url = null : $this->link_id = null;
        }
        
        return parent::save();
    }

    public function __get($column)
    {
        if($column == 'type_title' && !isset($this->_data['type_title'])) {
            $this->_data['type_title'] = JText::_('Redirect');
        }

        if($column == 'type_description' && !isset($this->_data['type_description'])) {
            $this->_data['type_description'] = JText::_('Redirect');
        }

        return parent::__get($column);
   }
}