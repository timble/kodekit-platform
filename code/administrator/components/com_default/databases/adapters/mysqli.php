<?php
/**
 * @version     $Id: lockable.php 2904 2011-03-16 19:28:22Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Default Database MySQLi Adapter
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultDatabaseAdapterMysqli extends KDatabaseAdapterMysqli
{ 
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
    		'connection'   => KFactory::get('lib.joomla.database')->getConnection(),
            'table_prefix' => KFactory::get('lib.joomla.database')->getPrefix(),
        ));
          
        parent::_initialize($config);
    }
}