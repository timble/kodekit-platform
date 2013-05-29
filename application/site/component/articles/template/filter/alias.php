<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Alias Template Filter Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ArticlesTemplateFilterAlias extends Library\TemplateFilterAlias
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addAlias(array('@highlight(' => '$this->getView()->highlight('), Library\TemplateFilter::MODE_COMPILE);
    }
}