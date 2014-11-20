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
 * Radiolist Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Nooku\Component\Categories
 */
class TemplateHelperRadiolist extends Library\TemplateHelperSelect
{
    public function categories($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'          => 'categories_category_id',
            'entity'           => '',
            'uncategorised' => false,
            'max_depth'     => '9',
        ))->append(array(
            'selected'      => $config->entity->{$config->name},
        ))->append(array(
            'filter' 	=> array(
                'sort'      => 'title',
                'parent'    => null,
                'published' => null,
                'table'     => $config->entity->getTable()->getBase()
            ),
        ));

        $categories = $this->getObject('com:categories.model.categories')
                        ->setState(Library\ObjectConfig::unbox($config->filter))
                        ->fetch();

        $iterator = new ModelIteratorNode($categories);
        $iterator->setMaxDepth($config->max_depth);

        $options = $this->options(array(
            'entity' => $iterator,
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