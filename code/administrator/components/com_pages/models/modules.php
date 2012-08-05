<?php
class ComPagesModelModules extends ComDefaultModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('module', 'int')
            ->insert('page', 'int');
    }

    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if($state->module) {
            $query->where('modules_module_id = :modules_module_id')->bind(array('modules_module_id' => $state->module));
        }

        if($state->page) {
            $query->where('pages_page_id = :pages_page_id')->bind(array('pages_page_id' => $state->page));
        }
    }
}
