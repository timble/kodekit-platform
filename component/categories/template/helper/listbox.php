<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Categories
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
     public function order($config = array())
     {
         $config = new Library\ObjectConfig($config);
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

        $identifier = 'com:'.$config->package.'.model.'.($config->model ? $config->model : Library\StringInflector::pluralize($config->package));

        $list = $this->getObject($identifier)->set($config->filter)->getRowset();

        $options = array();
        foreach($list as $item) {
			$options[] =  $this->option(array('label' => $item->ordering, 'value' => $item->ordering - $config->ordering));
		}
		
        $list = $this->optionlist(array(
            'options'  => $options,
            'name'     => $config->name,
            'attribs'  => $config->attribs,
            'selected' => $config->selected
        )); 
        return $list;
     }

    public function categories($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'      => 'category',
            'deselect'  => true,
            'selected'  => $config->category,
            'prompt'	=> '- Select -',
            'table'     => '',
            'parent'    => '',
            'max_depth' => 9,
        ));

        if($config->deselect) {
            $options[] = $this->option(array('label' => $this->translate($config->prompt), 'value' => 0));
        }

        $categories = $this->getObject('com:categories.model.categories')
                         ->table($config->table)
                        ->parent($config->parent)
                        ->sort('title')
                        ->getRowset();

        $iterator = new DatabaseIteratorNode($categories);
        $iterator->setMaxDepth($config->max_depth);

        foreach($iterator as $category)
        {
            $title =  substr('---------', 0, $iterator->getDepth()).$category->title;
            $options[] = $this->option(array('label' => $title, 'value' => $category->id));
        }

        $config->options = $options;

        return $this->optionlist($config);
    }
}
