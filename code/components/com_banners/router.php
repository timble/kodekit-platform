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
 
function BannersBuildRoute( &$query )
{
    $segments = array();

    if (isset($query['view'])) 
    {
        $segments[] = $query['view'];
        unset( $query['view'] );
    }
    
    if (isset($query['id'])) 
    {
        $segments[] = $query['id'];
        unset( $query['id'] );
    }

    return $segments;
}

function BannersParseRoute( $segments )
{
    $vars = array();

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