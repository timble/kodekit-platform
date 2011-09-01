<?php
/**
 * @version     $Id: sections.php 592 2011-03-16 00:30:35Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Cache Item Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
class ComCacheDatabaseRowItem extends KDatabaseRowAbstract
{	
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'identity_column'   => 'hash'
        ));

        parent::_initialize($config);
    }
    
    public function delete()
    { 
        KFactory::get('joomla:cache')->delete( $this->name );	
        return true; 
    }
}