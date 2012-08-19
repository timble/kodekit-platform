<?php
class ComLanguagesModelLanguages extends KModelTable
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('primary', 'boolean', null, true)
            ->insert('iso_code', 'com://admin/languages.filter.iso', null, true)
            ->insert('enabled', 'boolean')
            ->insert('application', 'cmd');
    }
    
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();
        
        if($state->primary)
        {
            $this->iso_code = JComponentHelper::getParams('com_languages')->getValue('primary_language', 'en-GB');
	        $state->remove('primary');
        }
        
        if(is_bool($state->enabled)) {
            $query->where('tbl.enabled = :enabled')->bind(array('enabled' => (int) $state->enabled));
        }
        
        if($state->application) {
            $query->where('tbl.application = :application')->bind(array('application' => $state->application));
        }
    }
}