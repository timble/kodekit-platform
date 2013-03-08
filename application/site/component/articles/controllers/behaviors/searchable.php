<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Searchable Controller Behavior Class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesControllerBehaviorSearchable extends Framework\ControllerBehaviorAbstract
{
    protected function _beforeControllerBrowse(Framework\CommandContext $context)
    {
        $request = $this->getRequest();
        if ($searchword = $request->query->get('searchword', 'string')) {
            $view = $this->getView();

            $view->setLayout('search');

            $this->getModel()->getTable()
                ->attachBehavior('com://site/articles.database.behavior.pageable',
                array('user' => $this->getUser()->getId()));
        }
    }
}