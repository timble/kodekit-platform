<?php
/**
 * @version     $Id: listbox.php 3031 2011-10-09 14:21:07Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function parents($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'    => 'parent_id'
        ));

        $pages = $this->getService('com://admin/pages.model.pages')
            ->enabled(true)
            ->menu($config->pages_menu_id)
            ->limit(0)
            ->getList();

        // @TODO: Extract page and its children.
        if($config->exclude)
        {
            if($row = $pages->find($config->pages_page_id)) {
                $pages->extract($row);
            }
        }

        $html     = array();
        $selected = $config->selected == 0 ? 'checked="checked"' : '';

        $html[] = '<label class="radio" for="'.$config->name.'0">';
        $html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.'0" value="0" '.$selected.' />';
        $html[] = JText::_('Top').'</label>';

        foreach($pages as $page)
        {
            $selected = $config->selected == $page->id ? 'checked="checked"' : '';

            $html[] = '<label class="radio level'.$page->level.'" for="'.$config->name.$page->id.'">';
            $html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.$page->id.'" value="'.$page->id.'" '.$selected.' />';
            $html[] = $page->title.'</label>';
        }

        return implode(PHP_EOL, $html);
    }
}
