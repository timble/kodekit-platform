<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Banners Router
.*
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 */
 
/**
 * @param   array   A named array
 * @return  array
 */
function BannersBuildRoute( &$query )
{
    $segments = array();

    if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset( $query['view'] );
    }
    if (isset($query['id'])) {
        $segments[] = $query['id'];
        unset( $query['id'] );
    }

    return $segments;
}

/**
 * @param   array   A named array
 * @param   array
 *
 * Formats:
 *
 * index.php?/banners/task/bid/Itemid
 *
 * index.php?/banners/bid/Itemid
 */
function BannersParseRoute( $segments )
{
    $vars = array();

    // view is always the first element of the array
    $count = count($segments);

    if ($count)
    {
        $count--;
        $segment = array_shift( $segments );
        if (is_numeric( $segment )) {
            $vars['id'] = $segment;
        } else {
            $vars['view'] = $segment;
        }
    }

    if ($count)
    {
        $count--;
        $segment = array_shift( $segments) ;
        if (is_numeric( $segment )) {
            $vars['id'] = $segment;
        }
    }

    return $vars;
}