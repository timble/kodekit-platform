<?php

class ComLanguagesDatabaseRowLangpack extends KDatabaseRowAbstract
{
	/**
     * Whitelist for keys to get from the xml manifest
     *
     * @var array
     */
    protected static $_manifest_fields = array(
    	'creationDate',
        'author',
        'copyright',
        'authorEmail',
        'authorUrl',
        'version',
        'description'
    );
    
	public function __get($column)
	{    
	    if ($column == 'name' && empty($this->_data['name'])) {
			$this->_data['name'] = basename($this->_data['path']);
		}
		
	    if($column == 'title' && empty($this->_data['title'])) 
	    {
            if($this->manifest instanceof SimpleXMLElement) {
                $this->_data['title'] = $this->manifest->name;
            } else {
                 $this->_data['title'] = '';
            }
	    }

		if ($column == 'manifest' && empty($this->_data['manifest'])) 
		{
            $file = $this->_data['path'].'/'.basename($this->_data['path']).'.xml';
           
            if(file_exists($file)) {
		        $this->_data['manifest'] = simplexml_load_file($file);
            } else {
                $this->_data['manifest'] = null;
            }
		}

		if (in_array($column, self::$_manifest_fields) && empty($this->_data[$column])) {
			$this->_data[$column] = $this->manifest->{$column};
		}

		return parent::__get($column);
	}
	
	/**
     * Languages are newer new
     *
     * @return boolean
     */
    public function isNew()
    {
        return false;
    }
    
	/**
     * Return an associative array of the data.
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
          
        //Include the manifest fields
        foreach(self::$_manifest_fields as $field) {
           $data[$field] = (string) $this->$field;
        }
        
        $data['name']      = (string) $this->name;
        $data['title']     = (string) $this->title;
        unset($data['path']);
          
        return $data;
    }
}