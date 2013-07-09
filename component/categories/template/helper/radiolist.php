<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
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
            'parent'        => null,
            'published'     => null,
            'max_depth'     => null,
            'sort'          => 'title',
            'uncategorised' => false,
        ))->append(array(
            'table'         => $config->row->getTable()->getBase(),
            'selected'      => $config->row->{$config->name},
        ));

        $html = array();

        if($config->uncategorised) {
            $category = array();
            $category['id'] = '0';
            $category['title'] = \JText::_('Uncategorized');

            $html[] = $this->_radiobox(array('category' => $category, 'config' => $config));
        }

        $list = $this->getObject('com:categories.model.categories')
                     ->table($config->table)
                     ->parent($config->parent)
                     ->published($config->published)
                     ->sort($config->sort)
                     ->getRowset();

        foreach($list as $category)
        {
            $html[] = $this->_radiobox(array('category' => $category, 'config' => $config));

            if($category->hasChildren()) {
                foreach($category->getChildren() as $category) {
                    $html[] = '<div style="margin-left: 16px">';
                    $html[] = $this->_radiobox(array('category' => $category, 'config' => $config));
                    $html[] = '</div>';
                }
            }
        }

        return implode(PHP_EOL, $html);
    }

    public function _radiobox($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'category'   => '',
            'config'     => '',
        ))->append(array(
             'id'        => $config->category->id,
             'title'     => $config->category->title,
             'name'      => $config->config->name,
             'selected'  => $config->config->selected,
        ));

        $html = array();
        $checked = ($config->id == $config->selected ? 'checked="checked"' : '');

        $html[] = '<label class="radio" for="'.$config->name.$config->id.'">';
        $html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.$config->id.'" value="'.$config->id.'" '.$checked.'/>';
        $html[] = $config->title;
        $html[] = '</label>';

        return implode(PHP_EOL, $html);
    }
}