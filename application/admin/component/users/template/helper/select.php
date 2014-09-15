<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Select Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Component\Users
 */
class UsersTemplateHelperSelect extends Library\TemplateHelperSelect
{    
    public function users($config = array())
    {
    	$config = new Library\ObjectConfig($config);

        $options_config = new Library\ObjectConfig(array(
            'entity' => $this->getObject('com:users.model.roles')->sort('id')->fetch(),
            'label'  => 'name',
            'value'  => 'id'));

        if ($name = $config->name)
        {
            $options_config->name = $name;
        }

        $config->options = $this->options($options_config);
    
    	return $this->checklist($config);
    }

    public function groups($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'  => 'role_id',
        ));

        $config->options = $this->options(array(
            'entity' => $this->getObject('com:users.model.roles')->sort('id')->fetch(),
            'label'   => 'name'
        ));

        return $this->radiolist($config);
    }
}