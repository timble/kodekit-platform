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
class ViewContextTemplate extends ViewContext
{
    /**
     * Set the view layout
     *
     * @param string $layout
     * @return ViewContext
     */
    public function setLayout($layout)
    {
        return ObjectConfig::set('layout', $layout);
    }

    /**
     * Get the view layout
     *
     * @return array
     */
    public function getLayout()
    {
        return ObjectConfig::get('layout');
    }
}