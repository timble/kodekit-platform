<?php
class ComLanguagesModelLanguages extends KModelTable
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('primary', 'boolean', null, true)
            ->insert('iso_code', 'com://admin/languages.filter.iso', null, true)
            ->insert('published', 'boolean');
    }
    
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();
        
        if($state->primary)
        {
            $this->iso_code = JComponentHelper::getParams('com_languages')->getValue('primary_language', 'en-GB');
	        $state->remove('primary');
        }
        
        if(is_bool($state->published)) {
            $query->where('tbl.enabled = :published')->bind(array('published' => (int) $state->published));
        }
        
        parent::_buildQueryWhere($query);
    }
}