<?php 
class ComLanguagesDatabaseRowsetLanguages extends KDatabaseRowsetAbstract
{
    public function __call($method, $arguments)
    {
        // Call these methods directly on the rowset.
        $methods = array('setActive', 'getActive', 'getPrimary');
        if(in_array($method, $methods) && isset($this->_mixed_methods[$method]))
        {
            $object = $this->_mixed_methods[$method];
            $result = null;

            $object->setMixer($this);

            switch(count($arguments))
            {
                case 0:
                    $result = $object->$method();
                    break;
                case 1:
                    $result = $object->$method($arguments[0]);
                    break;
             }
        }
        else $result = parent::__call($method, $arguments);

        return $result;
    }
}