<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;
use Kodekit\Component\Articles;

/**
 * Listbox Template Helper
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Platform\Articles
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function articles($config = array(), Library\TemplateInterface $template)
    {
    	$config = new Library\ObjectConfig($config);
    	$config->append(array(
    		'model' => 'articles',
    		'value'	=> 'id',
    		'label'	=> 'title'
    	));

    	return parent::render($config, $template);
    }

    public function authors($config = array(), Library\TemplateInterface $template)
    {
        $config = new Library\ObjectConfig($config);
		$config->append(array(
			'model'	=> 'articles',
			'name' 	=> 'created_by',
			'value'	=> 'created_by_id',
			'label'	=> 'created_by_name',
		));

		return parent::render($config, $template);
    }

    public function ordering($config = array())
    {
        $config = new Library\ObjectConfig($config);

        if (!$config->entity instanceof Articles\ModelEntityArticle) {
            throw new \InvalidArgumentException('The entity is missing.');
        }

        $article = $config->entity;

        $config->append(array(
            'name'     => 'order',
            'selected' => 0,
            'filter'   => array(
                'sort'      => 'ordering',
                'direction' => 'ASC',
                'category'  => $article->category_id)));

        $list = $this->getObject('com:articles.model.articles')
                     ->set($config->filter)
                     ->fetch();

        $options = array();
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

        $pages = $this->getObject('com:pages.model.pages')
            ->application('site')
            ->type('component')
            ->published(true)
            ->fetch();

        $pages = $pages->find(array(
            'state' => 'view=articles&layout=search'
        ));

        $options = array();
        foreach($pages as $page) {
            $options[] =  $this->option(array('label' => $page->title, 'value' => $page->id));
        }

        //Add the options to the config object
        $config->options = $options;

        return $this->optionlist($config);
    }
}