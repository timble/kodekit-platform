<?php
class ComPagesDatabaseRowPageUrl extends ComPagesDatabaseRowPageAbstract
{
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
                    $this->type_title = JText::_('External Link');
                    break;
                
                case 'type_description':
                    $this->type_description = JText::_('External Link');
                    break;
            }
        }
        
        return parent::__get($key);
    }
}