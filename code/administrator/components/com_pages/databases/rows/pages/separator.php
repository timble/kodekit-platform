<?php
class ComPagesDatabaseRowPageSeparator extends ComPagesDatabaseRowPageAbstract
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
                    $this->type_title = JText::_('Separator');
                    break;
                
                case 'type_description':
                    $this->type_description = JText::_('Separator');
                    break;
            }
        }
        
        return parent::__get($key);
    }
}