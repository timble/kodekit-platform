<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Route Helper Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesHelperRoute extends KObject
{
	public function getArticleRoute($id, $catid = 0, $sectionid = 0)
	{
		$needles = array(
			'article'  => (int) $id,
			'category' => (int) $catid,
			'section'  => (int) $sectionid,
		);

		$link = 'index.php?option=com_articles&view=article&id='. $id;
		if($catid) {
			$link .= '&catid='.$catid;
		}

		if($item = $this->_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	public function getSectionRoute($sectionid)
	{
		$needles = array(
			'section' => (int) $sectionid
		);

		$link = 'index.php?option=com_articles&view=section&id='.$sectionid;
		if($item = $this->_findItem($needles))
        {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}

			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	public function getCategoryRoute($catid, $sectionid)
	{
		$needles = array(
			'category' => (int) $catid,
			'section'  => (int) $sectionid
		);

		$link = 'index.php?option=com_articles&view=category&id='.$catid;
		if($item = $this->_findItem($needles))
        {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}

			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	public function _findItem($needles)
	{
		$component = JComponentHelper::getComponent('com_articles');

		$menus	= JApplication::getMenu('site', array());
		$items	= $menus->getItems('componentid', $component->id);

		$match = null;

		foreach($needles as $needle => $id)
		{
			foreach($items as $item)
			{
				if ((@$item->query['view'] == $needle) && (@$item->query['id'] == $id)) {
					$match = $item;
					break;
				}
			}

			if(isset($match)) {
				break;
			}
		}

		return $match;
	}
}