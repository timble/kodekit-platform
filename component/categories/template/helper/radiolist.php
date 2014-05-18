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
 * Radiolist Template Helper
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Nooku\Component\Categories
 */
class TemplateHelperRadiolist extends Library\TemplateHelperSelect
{
    public function categories($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'          => 'categories_category_id',
            'row'           => '',
            'uncategorised' => false,
            'max_depth'     => '9',
        ))->append(array(
            'selected'      => $config->row->{$config->name},
        ))->append(array(
            'filter' 	=> array(
                'sort'      => 'title',
                'parent'    => null,
                'published' => null,
                'table'     => $config->row->getTable()->getBase()
            ),
        ));

        $categories = $this->getObject('com:categories.model.categories')
                        ->setState(Library\ObjectConfig::unbox($config->filter))
                        ->getRowset();

        $iterator = new DatabaseIteratorNode($categories);
        $iterator->setMaxDepth($config->max_depth);

        $options = $this->options(array(
            'entity' => $iterator,
            'value'  => 'id',
            'label'  => 'title',
        ));

        if($config->uncategorised) {
            array_unshift($options, $this->option(array('label' => $this->translate('Uncategorized'), 'value' => '0', 'id' => '0')));
        }

        //Add the options to the config object
        $config->options = $options;

        return parent::radiolist($config);
    }
}