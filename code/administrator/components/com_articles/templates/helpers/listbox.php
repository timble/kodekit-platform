<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function authors($config = array())
    {
        $config = new KConfig($config);
		$config->append(array(
			'model'		=> 'articles',
			'name' 		=> 'created_by',
			'value'		=> 'created_by_id',
			'text'		=> 'created_by_name',
		));

		return parent::_listbox($config);
    }
    
    public function sections($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'      => 'section',
            'deselect'  => true,
            'selected'  => -1,
            'prompt'	=> '- Select -',
        	'uncategorised' => true
        ));

        $list = $this->getService('com://admin/articles.model.sections')
            ->set('scope', 'content')
            ->set('sort', 'title')
            ->getList();

        if($config->deselect) {
            $options[] = $this->option(array('text' => JText::_($config->prompt), 'value' => -1));
        }

        if($config->uncategorised) {
        	$options[] = $this->option(array('text' => JText::_('Uncategorised'), 'value' => 0));	
        }

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
            'prompt'	=> '- Select -'
        ));

        if($config->deselect) {
            $options[] = $this->option(array('text' => JText::_($config->prompt), 'value' => -1));
        }

        $options[] = $this->option(array('text' => JText::_('Uncategorised'), 'value' => 0));

        if($config->section != '0')
        {
            $list = $this->getService('com://admin/categories.model.categories')
                ->set('section', $config->section > 0 ? $config->section : 'com_content')
                ->set('sort', 'title')
                ->getList();

            foreach($list as $item) {
                $options[] = $this->option(array('text' => $item->title, 'value' => $item->id));
            }
        }
        else $config->selected = 0;

        $config->options = $options;

        return $this->optionlist($config);
    }

    public function states($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'      => 'state',
            'deselect'  => true,
            'selected'  => $config->state,
            'prompt'	=> '- Select -'
        ));

        if($config->deselect) {
            $options[] = $this->option(array('text' => JText::_($config->prompt)));
        }

        $options[] = $this->option(array('text' => JText::_('Published'), 'value' => 1));
        $options[] = $this->option(array('text' => JText::_('Unpublished'), 'value' => 0));
        $options[] = $this->option(array('text' => JText::_('Archived'), 'value' => -1));

        $config->options = $options;

        return $this->optionlist($config);
    }
}