<?php
/**
 * @package		Koowa_Model
 * @subpackage  Paginator
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Model Paginator Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Model
 * @subpackage  Paginator
 */
interface KModelPaginatorInterface
{
    /**
     * Get the pages
     *
     * @return KConfig A KConfig object that holds the page information
     */
    public function getPages();
}