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
    protected function _listbox($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'          => '',
            'state'         => null,
            'attribs'       => array(),
            'text'          => 'title',
            'value'         => 'id',
            'filter'        => array(),
            'app'           => $this->getIdentifier()->application,
            'deselect'      => true
        ))->append(array(	
            'column'        => $config->name,
            'listbox_title' => ucfirst($config->name),
            'listbox_sort'  => $config->text,
            'identifier'    => $config->app.'::com.'.$this->getIdentifier()->package.'.model.'.KInflector::pluralize($config->name)
        ))->append(array(
            'selected'      => $config->{$config->column},
	    ));

        $list = KFactory::tmp($config->identifier)
            ->limit(0)
            ->set($config->filter)
            ->sort($config->listbox_sort)
            ->getList();

        $options   = array();
        if($config->deselect){
            $options[] = $this->option(array('text' => '- '.JText::_( 'Select '.$config->listbox_title ).' -'));
		}

        foreach($list as $item) {
            $options[] =  $this->option(array('text' => $item->{$config->text}, 'value' => $item->{$config->value}));
        }

        $list = $this->optionlist(array(
            'options'       => $options,
            'name'          => $config->column,
            'selected'      => $config->selected,
            'attribs'       => $config->attribs
        ));

        return $list;
     }

     public function order($config = array())
     {
         $config = new KConfig($config);
         $config->append(array(
             'name'          => 'order',
             'state'         => null,
             'attribs'       => array(),
             'model'         => null,
             'selected'      => 0
        ));
        
        //@TODO can be removed when name collisions fixed
        $config->name = 'order'; 

        $app        = $this->getIdentifier()->application;
        $package    = $this->getIdentifier()->package;
        $identifier = $app.'::com.'.$package.'.model.'.($config->model ? $config->model : KInflector::pluralize($package));

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
