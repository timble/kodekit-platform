<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
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

    public function ordering($config = array())
    {
        $config = new KConfig($config);

        if (!$config->row instanceof ComArticlesDatabaseRowArticle) {
            throw new KTemplateHelperException('The row is missing.');
        }

        $article = $config->row;

        $config->append(array(
            'name'     => 'order',
            'selected' => 0,
            'filter'   => array(
                'sort'      => 'ordering',
                'direction' => 'ASC',
                'category'  => $article->category_id)));

        $list = $this->getService('com://admin/articles.model.articles')
                     ->set($config->filter)
                     ->getList();

        foreach ($list as $item)
        {
            $options[] = $this->option(array(
                'text'  => '( ' . $item->ordering . ' ) ' . $item->title,
                'value' => ($item->ordering - $article->ordering)));
        }

        $config->options = $options;

        return $this->optionlist($config);
    }
}