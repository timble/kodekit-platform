<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Model Paginator Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Model
 * @subpackage  Paginator
 */
interface ModelPaginatorInterface
{
    /**
     * Get the pages
     *
     * @return ObjectConfig A ObjectConfig object that holds the page information
     */
    public function getPages();
}