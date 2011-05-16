<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2009 - 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Weblink Route Helper
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */

class WeblinksHelperRoute
{
	function getWeblinkRoute($id, $catid) 
	{
		$needles = array(
			'category' => (int) $catid,
			'categories' => null
		);

		$itemid = WeblinksHelperRoute::_findItem($needles);
		$itemid = $itemid ? '&Itemid='.$itemid : '';

		$link = 'index.php?option=com_weblinks&view=weblink&id='. $id . '&catid='.$catid . $itemid;
		return $link;
	}

	function _findItem($needles)
	{
		static $items;

		if (!$items) {
			$items = JSite::getMenu()->getItems('componentid', JComponentHelper::getComponent('com_weblinks')->id);
		}

		if (!is_array($items)) {
			return null;
		}

		$match = null;
		foreach($needles as $needle => $id)
		{
			foreach($items as $item)
			{
				if ((@$item->query['view'] == $needle) && (@$item->query['id'] == $id)) {
					$match = $item->id;
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