<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Sites;

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Sites
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
  	public function sites( $config = array() )
   	{
     	$config = new Library\ObjectConfig($config);
       	$config->append(array(
          	'model'    => 'sites',
           	'name'     => 'site',
            'value'    => 'name',
        	'label'    => 'name',
          	'deselect' => false
       	));

      	return parent::_listbox($config);
 	}
}