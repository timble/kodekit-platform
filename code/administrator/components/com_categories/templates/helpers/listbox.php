<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories    
 */

class ComCategoriesTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
     public function order($config = array())
     {
         $config = new KConfig($config);
         $config->append(array(
             'name'          => 'order',
             'state'         => null,
             'attribs'       => array(),
             'model'         => null,
             'package'       => $this->getIdentifier()->package,
             'selected'      => 0
        ));
        
        //@TODO can be removed when name collisions fixed
        $config->name = 'order'; 

        $app        = $this->getIdentifier()->application;
        $identifier = $app.'::com.'.$config->package.'.model.'.($config->model ? $config->model : KInflector::pluralize($config->package));

        $list = KFactory::tmp($identifier)->limit(0)->set($config->filter)->getList();

        $options = array();
        foreach($list as $item) {
			$options[] =  $this->option(array('text' => $item->ordering, 'value' => $item->ordering - $config->ordering));
		}
		
        $list = $this->optionlist(array(
            'options'  => $options,
            'name'     => $config->name,
            'attribs'  => $config->attribs,
            'selected' => $config->selected
        )); 
        return $list;
     }
}
