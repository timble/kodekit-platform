<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Sites
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sites Template Listbox Helper Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Sites
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