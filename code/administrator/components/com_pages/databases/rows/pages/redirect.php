<?php
class ComPagesDatabaseRowPageRedirect extends ComPagesDatabaseRowPageAbstract
{
    public function save()
    {
        if($this->link_type) {
            $this->link_type == 'id' ? $this->link_url = null : $this->link_id = null;
        }

        return parent::save();
    }
    
    public function __get($key)
    {
        $this->disable();
        
        $object  = $this->getObject();
        $defined = array('type_description', 'type_title');
        
        if(in_array($key, $defined) && !isset($object->$key))
        {
            switch($key)
            {
                case 'type_title':
                    $this->type_title = JText::_('Redirect');
                    break;
                
                case 'type_description':
                    $this->type_description = JText::_('Redirect');
                    break;
            }
        }
        
        return parent::__get($key);
    }
}