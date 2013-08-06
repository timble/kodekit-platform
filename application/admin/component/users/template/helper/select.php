<?php
/**
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Select Template Helper Class
 *
 * @author      Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class UsersTemplateHelperSelect extends Library\TemplateHelperSelect
{    
    public function users($config = array())
    {
    	$config = new Library\ObjectConfig($config);
        $config->options = $this->options(array(
            'entity' => $this->getObject('com:users.model.roles')->sort('id')->getRowset(),
            'label'  => 'name',
            'value'  => 'id',
        ));
    
    	return $this->checklist($config);
    }

    public function groups($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'  => 'role_id',
        ));

        $config->options = $this->options(array(
            'entity' => $this->getObject('com:users.model.roles')->sort('id')->getRowset(),
            'label'   => 'name'
        ));

        return $this->radiolist($config);
    }
}