<?php
class ComLanguagesMixinComponents extends KMixinAbstract
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
}