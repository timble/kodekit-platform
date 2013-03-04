<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

/**
 * Listbox Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Sites
 */
class ComSitesTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
  	public function sites( $config = array() )
   	{
     	$config = new KConfig($config);
       	$config->append(array(
          	'model' => 'sites',
           	'name' => 'site',
            'value' => 'name',
        	'text' => 'name',
          	'deselect' => false
       	));

      	return parent::_listbox($config);
 	}
}