<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Component Database Row
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationDatabaseRowComponent extends Library\DatabaseRowAbstract
{
    public function isTranslatable()
    {
        $result = false;
        $tables = $this->getObject('com:languages.model.tables')
            ->reset()
            ->enabled(true)
            ->getRowset();
        
        if(count($tables->find(array('extensions_extension_id' => $this->id)))) {
            $result = true;
        }
        
        return $result;
    }
    
    public function __get($name)
    {
        if($name == 'params' && !($this->_data['params']) instanceof JParameter)
        {
            $path = Library\ClassLoader::getInstance()->getApplication('admin');
            $file = $path.'/component/'.$this->option.'/resources/config/settings.xml';

            $this->_data['params'] = new JParameter( $this->_data['params'], $file, 'component' );
        }
        
        return parent::__get($name);
    }
}