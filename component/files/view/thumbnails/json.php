<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Thumbnails Json View
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
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