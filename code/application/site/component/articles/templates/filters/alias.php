<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Alias Template Filter Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesTemplateFilterAlias extends KTemplateFilterAlias
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->addAlias(array('@highlight(' => '$this->getView()->highlight('), KTemplateFilter::MODE_READ);
    }
}