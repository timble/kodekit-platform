<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-categories for the canonical source repository
 */

namespace Kodekit\Component\Categories;

use Kodekit\Library;

/**
 * Radiolist Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Kodekit\Component\Categories
 */
class TemplateHelperRadiolist extends Library\TemplateHelperSelect
{
    public function categories($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'          => 'categories_category_id',
            'entity'        => '',
            'uncategorised' => false,
            'max_level'     => '9',
        ))->append(array(
            'selected'      => $config->entity->{$config->name},
        ))->append(array(
            'filter'    => array(
                'sort'      => 'title',
                'limit'     => 0,
                'parent'    => null,
                'published' => null,
                'table'     => $config->entity->getTable()->getBase()
            ),
        ));

        $categories = $this->getObject('com:categories.model.categories')
                        ->setState(Library\ObjectConfig::unbox($config->filter))
                        ->fetch();

        $options = $this->options(array(
            'entity' =>  $categories->getRecursiveIterator($config->max_level),
            'value'  => 'id',
            'label'  => 'title',
        ));

        if($config->uncategorised)
        {
            $label = $this->getObject('translator')->translate('Uncategorized');
            array_unshift($options, $this->option(array('label' => $label, 'value' => '0', 'id' => '0')));
        }

        //Add the options to the config object
        $config->options = $options;

        return parent::radiolist($config);
    }
}