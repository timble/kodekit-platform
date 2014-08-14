<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * View Context
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\View
 */
class ViewContext extends Command implements ViewContextInterface
{
    /**
     * Set the view data
     *
     * @param array $data
     * @return ViewContext
     */
    public function setData($data)
    {
        return ObjectConfig::set('data', $data);
    }

    /**
     * Get the view data
     *
     * @return array
     */
    public function getData()
    {
        return ObjectConfig::get('data');
    }
}