<?php
class ComLanguagesViewComponentsHtml extends ComDefaultViewHtml
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
        $tables     = $this->getService('com://admin/languages.model.tables')->getList();
        $components = $this->getService('com://admin/extensions.model.components')
            ->id(array_unique($tables->components_component_id))
            ->getList();
        
        foreach($tables as $table) {
            $components->find($table->components_component_id)->enabled = $table->enabled;
        }
        
        $this->assign('components', $components);
        $this->assign('total', count($components));
        
        return parent::display();
    }
}