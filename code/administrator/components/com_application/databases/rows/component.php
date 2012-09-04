<?php
class ComApplicationDatabaseRowComponent extends KDatabaseRowAbstract
{
    public function isTranslatable()
    {
        $result = false;
        if($this->getService('application')->getCfg('multilanguage'))
        {
            $tables = $this->getService('com://admin/languages.model.tables')
                ->reset()
                ->enabled(true)
                ->getList();
            
            if(count($tables->find(array('extensions_component_id' => $this->id)))) {
                $result = true;
            }
        }
        
        return $result;
    }
    
    public function __get($name)
    {
        if($name == 'params' && !($this->_data['params']) instanceof JParameter)
        {
	        $file = JPATH_APPLICATION.'/components/'.$this->option.'/config.xml';
	        $this->_data['params'] = new JParameter( $this->_data['params'], $file, 'component' );
        }
        
        return parent::__get($name);
    }
}