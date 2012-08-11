<?php

class ComLanguagesModelTables extends KModelTable
{
    /**
     * List of untranslatable tables
     *
     * @var	array
     */
    protected $_untranslatable = array(
		'components',
		'core_acl_aro',
		'core_acl_aro_groups',
		'core_acl_aro_map',
		'core_acl_aro_sections',
		'core_acl_groups_aro_map',
		'groups',
        'languages_items',
		'languages_languages',
		'languages_tables',
		'menu_types',
		'modules_menu',
		'plugins',
		'users',
        'users_session'
    );
    
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->getState()
            ->insert('published', 'boolean')
            ->insert('translated', 'boolean');
    }
    
    public function getList()
    {
        if(!isset($this->_list) && $this->getState()->translated === false)
        {
            $model = clone $this;
            $translated = $model->reset()->getList()->table_name;
            
            $database = $this->getTable()->getDatabase();
            $prefix = $database->getTablePrefix();
            
            $query = $this->getService('koowa:database.query.show')
                ->show('TABLE STATUS')
                ->like(':like')
                ->bind(array('like' => '%'));
            
            $data = array();
            foreach($database->select($query, KDatabase::FETCH_OBJECT_LIST) as $index => $table)
            {
                $name = substr($table->Name, strlen($prefix));
                if(!in_array($name, $this->_untranslatable) && !in_array($name, $translated) && !$this->_isTranslated($name))
                {
                    $data[] = array(
                        'id' => $index,
                        'table_name' => $name
                    );
                }
            }
            
            $this->_list = $this->getTable()->getRowset()->addData($data, false);
        }
        
        return parent::getList();
    }
    
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();
        
        if(!$state->isUnique())
        {
            if(is_bool($state->published)) {
                $query->where('tbl.enabled = :enabled')->bind(array('enabled' => (int) $state->published));
            }
        }
        
        parent::_buildQueryWhere($query);
    }

    /**
     * Check if a table is translated (= contains an ISO code after the prefix)
     *
     * @param	string	Full table name
     * @return	boolean	True if translated
     */
    protected function _isTranslated($table)
    {
        static $languages;

        if(!isset($languages)) {
            $languages = $this->getService('com://admin/languages.model.languages')->published(true)->getList();
        }

        $result = false;
        foreach($languages as $language)
        {
            if (strpos(strtolower($table), strtolower($language->iso_code).'_') === 0)
            {
                $result = true;
                break;
            }
        }
        
        return $result;
    }
}