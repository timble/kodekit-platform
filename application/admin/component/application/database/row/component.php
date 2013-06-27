<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Component Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationDatabaseRowComponent extends Library\DatabaseRowAbstract
{
    public function isTranslatable()
    {
        $result = false;
        $tables = $this->getObject('com:languages.model.tables')
            ->reset()
            ->enabled(true)
            ->fetch();
        
        if(count($tables->find(array('extensions_extension_id' => $this->id)))) {
            $result = true;
        }
        
        return $result;
    }
}