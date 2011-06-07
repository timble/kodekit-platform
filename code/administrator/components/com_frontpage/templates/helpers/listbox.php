<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper
 *
 * @author      Richie Mortimer <http://nooku.assembla.com/profile/ravenlife>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 */

class ComFrontpageTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function sections($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'      => 'section',
            'deselect'  => true,
            'selected'  => -1,
            'prompt'    => '- Select -'
        ));

        $list = KFactory::tmp('admin::com.sections.model.sections')
            ->set('scope', 'content')
            ->set('sort', 'title')
            ->set('limit', 0)
            ->getList();

        if($config->deselect) {
            $options[] = $this->option(array('text' => JText::_($prompt), 'value' => -1));
        }

        $options[] = $this->option(array('text' => JText::_('Uncategorised'), 'value' => 0));

        foreach($list as $item) {
            $options[] = $this->option(array('text' => $item->title, 'value' => $item->id));
        }

        $config->options = $options;

        return $this->optionlist($config);
    }

    public function categories($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'      => 'category',
            'deselect'  => true,
            'selected'  => $config->category,
            'prompt'    => '- Select -'
        ));

        if($config->deselect) {
            $options[] = $this->option(array('text' => JText::_($prompt), 'value' => -1));
        }

        $options[] = $this->option(array('text' => JText::_('Uncategorised'), 'value' => 0));

        if($config->section != '0') 
        {
            $list = KFactory::tmp('admin::com.categories.model.categories')
                ->set('section', $config->section > 0 ? $config->section : 'com_content')
                ->set('sort', 'title')
                ->set('limit', 0)
                ->getList();

            foreach($list as $item) {
                $options[] = $this->option(array('text' => $item->title, 'value' => $item->id));
            }
        }
        else $config->selected = 0;

        $config->options = $options;

        return $this->optionlist($config);
    }

    public function authors($config)
    {
        $config = new KConfig($config);
        $config->append(array(
            'prompt'    => '- Select Author -'
        ));

        $db = KFactory::get('lib.koowa.database.adapter.mysqli');

        $query  = $db->getQuery()->select('tbl.created_by AS value')
              ->select('u.name AS text')
              ->from('content AS tbl')
              ->join('LEFT', 'users AS u', 'u.id = tbl.created_by')
              ->where('tbl.state', '!=', '-1')
              ->where('tbl.state', '!=', '-2')
              ->group('u.name')
              ->order('u.name');

        $default[] = array('value' => '', 'text' => JText::_($prompt));
        $options = $db->select($query, KDatabase::FETCH_ARRAY_LIST);

        $config->options = array_merge($default, $options);

        return $this->optionlist($config);
    }
}