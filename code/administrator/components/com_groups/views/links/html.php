<?php
class ComGroupsViewLinksHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $from = array();
        foreach(KFactory::tmp('admin::com.groups.model.groups')->set('core', true)->getList() as $id => $group)
        {
            $from[] = array('id' => $id, 'title' => $group->name);
        }
        $to = array();
        foreach(KFactory::tmp('admin::com.groups.model.groups')->set('core', false)->getList() as $id => $group)
        {
            $to[] = array('id' => $id, 'title' => $group->name);
        }
    
        $this->assign(array(
            'from' => $from,
            'to'   => $to
        ));


        return parent::display();
    }
}