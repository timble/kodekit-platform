<?php
class ComLanguagesConfigTable extends KObject implements KServiceInstantiatable
{
    protected $_translatable;
    
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        if(!$container->has($config->service_identifier)) 
        {
            $instance = new self($config);
            $container->set($config->service_identifier, $instance);
        }
        
        return $container->get($config->service_identifier);
    }
    
    public function setTranslatable($tables)
    {
        $this->_translatable = $tables;
    }
    
    public function getTranslatable()
    {
        /*if(!isset($this->_translatable))
        {
            $tables = $this->getService('com://admin/languages.model.tables')
                ->published(true)
                ->sort('table_name')
                ->getList();
            
            $this->setTranslatable($tables->getColumn('table_name'));
        }*/
        
        return $this->_translatable;
    }
}