<?php
class ComLanguagesConfigLanguage extends KObject implements KServiceInstantiatable
{
    protected $_primary;
    
    protected $_active;
    
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        if(!$container->has($config->service_identifier)) {
            $container->set($config->service_identifier, new self($config));
        }
        
        return $container->get($config->service_identifier);
    }
    
    public function setActive(KDatabaseRowTable $language)
    {
        $this->_active = $language;
        /*list($language, $country) = explode('-', $iso_code, 2);
        $this->_active = $language.'-'.strtoupper($country);*/
    }
    
    public function getActive()
    {
        if(!$this->_active) {
            KRequest::get('get.lang', 'com://admin/languages.filter.iso', $this->getPrimary());
        }
        
        return $this->_active;
    }
    
    public function setPrimary($iso_code)
    {
        list($language, $country) = explode('-', $iso_code, 2);
        $this->_primary = $language.'-'.strtoupper($country);
    }
    
    public function getPrimary()
    {
        if(!$this->_primary) {
            $this->_primary = $this->getService('com://admin/languages.model.languages')->primary(true)->getItem();
        }
        
        return $this->_primary;
    }
}