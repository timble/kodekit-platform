<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Thumbnails Json View
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ViewThumbnailsJson extends ViewJson
{
    protected function _getList()
    {
        $list = $this->getModel()->fetch();

        $results = array();
        foreach ($list as $item) 
        {
        	$key = $item->filename;
        	$results[$key] = $item->toArray();
        }

        ksort($results);

    	$data = parent::_getList();

        $data['items'] = $results;
        $data['total'] = count($list);

        return $data;
    }
}