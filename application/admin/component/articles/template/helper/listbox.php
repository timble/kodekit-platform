<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Articles
 */
class ArticlesTemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function articles($config = array())
    {
    	$config = new Library\ObjectConfig($config);
    	$config->append(array(
    		'model' => 'articles',
    		'value'	=> 'id',
    		'label'	=> 'title'
    	));
    
    	return parent::_render($config);
    }
    
    public function authors($config = array())
    {
        $config = new Library\ObjectConfig($config);
		$config->append(array(
			'model'	=> 'articles',
			'name' 	=> 'created_by',
			'value'	=> 'created_by_id',
			'label'	=> 'created_by_name',
		));

		return parent::_listbox($config);
    }

    public function ordering($config = array())
    {
        $config = new Library\ObjectConfig($config);

        if (!$config->row instanceof ArticlesDatabaseRowArticle) {
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

        $list = $this->getObject('com:articles.model.articles')
                     ->set($config->filter)
                     ->getRowset();

        foreach ($list as $item)
        {
            $options[] = $this->option(array(
                'label' => '( ' . $item->ordering . ' ) ' . $item->title,
                'value' => ($item->ordering - $article->ordering)));
        }

        $config->options = $options;

        return $this->optionlist($config);
    }

    public function searchpages($config = array())
    {
        $config = new Library\ObjectConfig($config);

        $pages = $this->getObject('com:pages.model.pages')->application('site')->type('component')->published(true)->getRowset();
        $pages = $pages->find(array(
            'link_url' => 'option=com_articles&view=articles&layout=search'));

        $options = array();

        foreach($pages as $page) {
            $options[] =  $this->option(array('label' => $page->title, 'value' => $page->id));
        }

        //Add the options to the config object
        $config->options = $options;

        return $this->optionlist($config);
    }
}