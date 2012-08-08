<?php
class JElementPage extends JElement
{
    var $_name = 'Page';
    
    function fetchElement($name, $value, &$node, $control_name)
    {
        $listbox = KService::get('com://admin/pages.template.helper.listbox')
            ->pages(array('disable' => array('separator', 'url')));
        
        return $listbox;
    }
}