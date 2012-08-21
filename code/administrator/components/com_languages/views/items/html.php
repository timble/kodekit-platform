<?php
class ComLanguagesViewItemsHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $model = $this->getModel();
        
        $languages = $this->getService('com://admin/languages.model.languages')
            ->iso_code($model->getList()->iso_code)
            ->getList();
        
        $this->assign('languages', $languages);
        
        $tables = $this->getService('com://admin/languages.model.tables')
            ->getList();
            
        $this->assign('tables', $tables);
        
        $state = $model->getState();
        $this->assign('group_tables', $state->sort == 'table' && !$state->iso_code && !$state->status && !$state->search);
        
        return parent::display();
    }
    
    /*public function display($tpl = null)
    {
        $nooku = KFactory::get('admin::com.nooku.model.nooku');
        $model = KFactory::get('admin::com.nooku.model.nodes');
         
        $filters = $model->getFilters();

        $this->assignRef('filters'    , $filters);
        $this->assignRef('items'      , $model->getList());
        $this->assignRef('pagination' , $model->getPagination());
        
        // This allows us to group items when sorting by table
        $group_tables = ($filters['order']=='table_name' && !$filters['iso_code'] && !$filters['status'] && !$filters['translator'] && !$filters['filter']);
        $this->assign('group_tables'  , $group_tables);
        
        // Mixin a menubar object
        $this->mixin( new NookuMixinMenu($this));

        $this->displayMenubar();
        $this->displayMenutitle();
        $this->displayToolbar();

        // Display the layout
        parent::display($tpl);
    }*/
}