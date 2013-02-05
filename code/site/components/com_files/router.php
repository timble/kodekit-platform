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
 * Files router class.
 *
 * @author     Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package    Nooku_Server
 * @subpackage Articles
 */

class ComFilesRouter extends ComDefaultRouter
{
	protected function _encodeString($string)
	{
		$string = str_replace("\'", "'", $string);
		$string = str_replace('\"', '\"', $string);
		$string = str_replace('%2F', '/', rawurlencode($string));
		$string = str_replace('.', '%252E', $string);
	
		return $string;
	}
	
	protected function _decodeString($string)
	{
		$string = str_replace('/', '%2F', rawurldecode($string));
		$string = str_replace('%252E', '.', $string);
		$string = str_replace('%20', ' ', $string);
	
		return $string;
	}
	
	public function buildRoute(&$query)
	{
		$segments = array();

		if (empty($query['Itemid'])) {
			return $segments;
		}
		
		$page       = $this->getService('application.pages')->getPage($query['Itemid']);
		$menu_query = $page->getLink()->query;
		
		if (isset($query['view']) && $query['view'] === 'file') {
			$segments[] = 'file';
		}
		
		if (isset($query['layout']) && isset($menu_query['layout']) && $query['layout'] === $menu_query['layout']) {
			unset($query['layout']);
		}
		
		if (isset($query['folder']))
		{
			if (empty($menu_query['folder'])) {
				$segments[] = str_replace('%2F', '/', $query['folder']);
			}
			else if ($query['folder'] == $menu_query['folder']) { 
				// do nothing
			}
			else if (strpos($query['folder'], $menu_query['folder']) === 0) {
				$relative = substr($query['folder'], strlen($menu_query['folder'])+1, strlen($query['folder']));
				$relative = str_replace($menu_query['folder'].'/', '', $query['folder']);
		
				$segments[] = $this->_encodeString($relative);
			}
		}
		
		if (isset($query['name']))
		{
			$name = $this->_encodeString($query['name']);
			$segments[] = $name;
		}

		unset($query['view']);
		unset($query['folder']);
		unset($query['name']);

		return $segments;
	}

    public function parseRoute($segments)
    {
		$vars  = array();
		$page  = $this->getService('application.pages')->getActive();
		$query = $page->getLink()->query;
		
		if ($segments[0] === 'file')
		{ // file view
			$vars['view']    = array_shift($segments);
			$vars['name']    = $this->_decodeString(array_pop($segments));
			$vars['folder']  = $query['folder'] ? $query['folder'].'/' : '';
			$vars['folder'] .= implode('/', $segments);
		}
		else
		{ // directory view
			$vars['view']   = 'directory';
			$vars['layout'] = $query['layout'];
			$vars['folder'] = $query['folder'].'/'.implode('/', $segments);
		}
		$vars['folder'] = str_replace('%2E', '.', $vars['folder']);
		$vars['layout'] = $query['layout'];

		return $vars;
    }
}