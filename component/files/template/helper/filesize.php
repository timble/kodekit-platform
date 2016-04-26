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
 * Filesize Helper
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class TemplateHelperFilesize extends Library\TemplateHelperAbstract
{
    public function humanize($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'sizes' => array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB')
        ));
        $bytes = $config->size;
        $result = '';
        $format = (($bytes > 1024*1024 && $bytes % 1024 !== 0) ? '%.2f' : '%d').' %s';

        foreach ($config->sizes as $s)
        {
            $size = $s;
            if ($bytes < 1024) {
                $result = $bytes;
                break;
            }
            $bytes /= 1024;
        }

        if ($result == 1) {
            $size = Library\StringInflector::singularize($size);
        }

        return sprintf($format, $result, $this->getObject('translator')->translate($size));
    }
}