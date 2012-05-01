<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Contacts Rss View
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ComContactsViewContactsRss extends KViewAbstract
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
		$category = $this->getService('com://site/contacts.model.categories')
	                     ->id($this->getModel()->getState()->category)
	                     ->getItem();
		
		$contacts = $this->getModel()->getList();

		$xml  = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
		$xml .= '<rss version="2.0">'.PHP_EOL;
		$xml .= '<channel>'.PHP_EOL;
		$xml .= '	<title>'.htmlspecialchars($category->title).'</title>'.PHP_EOL;
		$xml .= '	<description><![CDATA['.$category->description.']]></description>'.PHP_EOL;
		$xml .= '	<link>'.KRequest::url().'</link>'.PHP_EOL;
		$xml .= '	<lastBuildDate>'.date('r').'</lastBuildDate>'.PHP_EOL;
		$xml .= '	<generator>'.JURI::base().'</generator>'.PHP_EOL;
		$xml .= '	<language>'.JFactory::getLanguage()->getTag().'</language>'.PHP_EOL;

		foreach($contacts as $contact)
		{
			$xml .= '	<item>'.PHP_EOL;
			$xml .= '		<title>'.htmlspecialchars($contact->title).'</title>'.PHP_EOL;
			$xml .= '		<link>'.$this->getRoute('view=weblink&category='.$category->id.':'.$category->slug.'&id='.$contact->id.':'.$contact->slug).'</link>'.PHP_EOL;
			$xml .= '		<guid>'.$this->getRoute('view=weblink&category='.$category->id.':'.$category->slug.'&id='.$contact->id.':'.$contact->slug).'</guid>'.PHP_EOL;
			$xml .= '		<description><![CDATA['.htmlspecialchars($contact->description).']]></description>'.PHP_EOL;
			$xml .= '		<category>'.$category->title.'</category>'.PHP_EOL;
			$xml .= '		<pubDate>'.date('r',strtotime($contact->date)).'</pubDate>'.PHP_EOL;
			$xml .= '	</item>'.PHP_EOL;
		}

		$xml .= '</channel>'.PHP_EOL;
		$xml .= '</rss>';

    	$this->output = $xml;

    	return parent::display();
    }
}