<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Listbox Template Helper
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesTemplateHelperListbox extends ComBaseTemplateHelperListbox
{
    public function articles($config = array())
    {
    	$config = new Framework\Config($config);
    	$config->append(array(
    		'model' 	=> 'articles',
    		'value'		=> 'id',
    		'text'		=> 'title'
    	));
    
    	return parent::_render($config);
    }
    
    public function authors($config = array())
    {
        $config = new Framework\Config($config);
		$config->append(array(
			'model'		=> 'articles',
			'name' 		=> 'created_by',
			'value'		=> 'created_by_id',
			'text'		=> 'created_by_name',
		));

		return parent::_listbox($config);
    }

    public function ordering($config = array())
    {
        $config = new Framework\Config($config);

        if (!$config->row instanceof ComArticlesDatabaseRowArticle) {
            throw new \InvalidArgumentException('The row is missing.');
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
                     ->getRowset();

        foreach ($list as $item)
        {
            $options[] = $this->option(array(
                'text'  => '( ' . $item->ordering . ' ) ' . $item->title,
                'value' => ($item->ordering - $article->ordering)));
        }

        $config->options = $options;

        return $this->optionlist($config);
    }

    public function searchpages($config = array())
    {
        $config = new Framework\Config($config);

        $pages = $this->getService('com://admin/pages.model.pages')->application('site')->type('component')->published(true)->getRowset();
        $pages = $pages->find(array(
            'link_url' => 'option=com_articles&view=articles&layout=search'));

        $options = array();

        foreach($pages as $page)
        {
            $options[] =  $this->option(array('text' => $page->title, 'value' => $page->id));
        }

        //Add the options to the config object
        $config->options = $options;

        return $this->optionlist($config);
    }
}