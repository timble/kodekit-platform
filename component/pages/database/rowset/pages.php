<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Pages Database Rowset
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseRowsetPages extends Library\DatabaseRowsetTable
{
    public function find($needle)
    {
        if(is_array($needle) && array_key_exists('link', $needle) && is_array($needle['link']))
        {
            $query = $needle['link'];
            unset($needle['link']);

            $pages = parent::find($needle);
            foreach($pages as $page)
            {
                foreach($query as $parts)
                {
                    $result = $page;
                    foreach($parts as $key => $value)
                    {
                        if(!(isset($page->getLink()->query[$key]) && $page->getLink()->query[$key] == $value))
                        {
                            $result = null;
                            break;
                        }
                    }

                    if(!is_null($result)) {
                        break(2);
                    }
                }
            }
        }
        else $result = parent::find($needle);

        return $result;
    }
}
