<?php
class ComLanguagesViewTablesHtml extends ComDefaultViewHtml
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'auto_assign' => false
        ));
        
        parent::_initialize($config);
    }
    
    public function display()
    {
        $query = $this->getService('koowa:database.query.select')
            ->distinct()
            ->columns(array('components_component_id', 'enabled'))
            ->group('components_component_id');
        
        $list = $this->getModel()->getTable()->select($query);
        
        $components = $this->getService('com://admin/extensions.model.components')
            ->id(array_values($list->components_component_id))
            ->getList();
        
        foreach($list as $item) {
            $components->find($item->components_component_id)->enabled = $item->enabled;
        }
        
        $this->assign('components', $components);
        $this->assign('total', count($list));
        
        return parent::display();
    }
}