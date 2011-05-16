<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2009 - 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Weblink's category rss layout
 *
 * @author    	Jeremy Wilken <www.gnomeontherun.com>
 * @category 	Nooku
 * @package     Nooku_Components
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
		$category = KFactory::get('site::com.weblinks.model.categories')->getItem();
		$items = KFactory::get('site::com.weblinks.model.weblinks')->catid(KRequest::get('get.id', 'int'))->getList();

		$xml  = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
		$xml .= '<rss version="2.0">'.PHP_EOL;
		$xml .= '<channel>'.PHP_EOL;
		$xml .= '	<title>'.$category->title.'</title>'.PHP_EOL;
		$xml .= '	<description><![CDATA['.$category->description.']]></description>'.PHP_EOL;
		$xml .= '	<link>'.KRequest::url().'</link>'.PHP_EOL;
		$xml .= '	<lastBuildDate>'.date('r').'</lastBuildDate>'.PHP_EOL;
		$xml .= '	<generator>'.JURI::base().'</generator>'.PHP_EOL;
		$xml .= '	<language>'.KFactory::get('lib.joomla.language')->getTag().'</language>'.PHP_EOL;

		foreach($items as $item)
		{
			$xml .= '	<item>'.PHP_EOL;
			$xml .= '		<title>'.htmlspecialchars($item->title).'</title>'.PHP_EOL;
			$xml .= '		<link>'.JURI::base().JRoute::_('index.php?option=com_weblinks&view=weblink&id='.$item->id).'</link>'.PHP_EOL;
			$xml .= '		<guid>'.JURI::base().JRoute::_('index.php?option=com_weblinks&view=weblink&id='.$item->id).'</guid>'.PHP_EOL;
			$xml .= '		<description><![CDATA['.htmlspecialchars($item->description).']]></description>'.PHP_EOL;
			$xml .= '		<category>'.$category->title.'</category>'.PHP_EOL;
			$xml .= '		<pubDate>'.date('r',strtotime($item->date)).'</pubDate>'.PHP_EOL;
			$xml .= '	</item>'.PHP_EOL;
		}

		$xml .= '</channel>'.PHP_EOL;
		$xml .= '</rss>';

    	$this->output = $xml;

    	return parent::display();
    }
}