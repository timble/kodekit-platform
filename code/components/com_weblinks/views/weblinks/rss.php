<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Weblink's category rss layout
 *
 * @author    	Jeremy Wilken <www.gnomeontherun.com>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksViewWeblinksRss extends KViewAbstract
{
	protected function _initialize(KConfig $config)
    {
    	$config->append(array(
			'mimetype'	  => 'application/rss+xml',
       	));

    	parent::_initialize($config);
    }

	public function display()
    {
		$category = $this->getService('com://site/weblinks.model.categories')
	                     ->id($this->getModel()->getState()->category)
	                     ->getItem();
		
		$weblinks = $this->getModel()->getList();

		$xml  = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
		$xml .= '<rss version="2.0">'.PHP_EOL;
		$xml .= '<channel>'.PHP_EOL;
		$xml .= '	<title>'.$category->title.'</title>'.PHP_EOL;
		$xml .= '	<description><![CDATA['.$category->description.']]></description>'.PHP_EOL;
		$xml .= '	<link>'.KRequest::url().'</link>'.PHP_EOL;
		$xml .= '	<lastBuildDate>'.date('r').'</lastBuildDate>'.PHP_EOL;
		$xml .= '	<generator>'.JURI::base().'</generator>'.PHP_EOL;
		$xml .= '	<language>'.JFactory::getLanguage()->getTag().'</language>'.PHP_EOL;

		foreach($weblinks as $weblink)
		{
			$xml .= '	<item>'.PHP_EOL;
			$xml .= '		<title>'.htmlspecialchars($weblink->title).'</title>'.PHP_EOL;
			$xml .= '		<link>'.$this->getRoute('view=weblink&category='.$category->id.':'.$category->slug.'&id='.$weblink->id.':'.$weblink->slug).'</link>'.PHP_EOL;
			$xml .= '		<guid>'.$this->getRoute('view=weblink&category='.$category->id.':'.$category->slug.'&id='.$weblink->id.':'.$weblink->slug).'</guid>'.PHP_EOL;
			$xml .= '		<description><![CDATA['.htmlspecialchars($weblink->description).']]></description>'.PHP_EOL;
			$xml .= '		<category>'.$category->title.'</category>'.PHP_EOL;
			$xml .= '		<pubDate>'.date('r',strtotime($weblink->date)).'</pubDate>'.PHP_EOL;
			$xml .= '	</item>'.PHP_EOL;
		}

		$xml .= '</channel>'.PHP_EOL;
		$xml .= '</rss>';

    	$this->output = $xml;

    	return parent::display();
    }
}