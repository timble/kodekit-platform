<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationDatabaseRowComponent extends KDatabaseRowAbstract
{
    public function isTranslatable()
    {
        $result = false;
        $tables = $this->getService('com://admin/languages.model.tables')
            ->reset()
            ->enabled(true)
            ->getRowset();
        
        if(count($tables->find(array('extensions_component_id' => $this->id)))) {
            $result = true;
        }
        
        return $result;
    }
    
    public function __get($name)
    {
        if($name == 'params' && !($this->_data['params']) instanceof JParameter)
        {
            $path = $this->getIdentifier()->getApplication('admin');
            $file = $path.'/components/'.$this->option.'/config.xml';

            $this->_data['params'] = new JParameter( $this->_data['params'], $file, 'component' );
        }
        
        return parent::__get($name);
    }
}