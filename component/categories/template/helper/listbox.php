<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Categories
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
     public function order($config = array())
     {
         $config = new Library\ObjectConfig($config);
         $config->append(array(
             'name'     => 'order',
             'state'    => null,
             'attribs'  => array(),
             'model'    => null,
             'package'  => $this->getIdentifier()->package,
             'selected' => 0
        ));

        //@TODO can be removed when name collisions fixed
        $config->name = 'order';

        $identifier = 'com:'.$config->package.'.model.'.($config->model ? $config->model : Library\StringInflector::pluralize($config->package));

        $list = $this->getObject($identifier)->set($config->filter)->fetch();

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
            'max_level' => 9,
        ))->append(array(
            'filter' 	=> array(
                'sort'      => 'title',
                'limit'     => 0,
                'parent'    => null,
                'published' => null,
                'table'     => $config->entity->getTable()->getBase()
            ),
        ));

        if($config->deselect) {
            $options[] = $this->option(array('label' => $this->getObject('translator')->translate($config->prompt), 'value' => 0));
        }

        $categories = $this->getObject('com:categories.model.categories')
                        ->setState(Library\ObjectConfig::unbox($config->filter))
                        ->fetch();

        $iterator = $categories->getRecursiveIterator($config->max_level);
        foreach($iterator as $category)
        {
            $title =  substr('---------', 0, $iterator->getDepth()).$category->title;
            $options[] = $this->option(array('label' => $title, 'value' => $category->id));
        }

        $config->options = $options;

        return $this->optionlist($config);
    }
}
