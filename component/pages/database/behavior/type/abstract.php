<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Abstract Typable Database Behavior
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
abstract class DatabaseBehaviorTypeAbstract extends Library\DatabaseBehaviorAbstract implements DatabaseBehaviorTypeInterface
{
    abstract function getTitle();

    abstract function getDescription();

    public function getParams($group)
    {
        return null;
    }

    public function getLink()
    {
        return null;
    }

    protected function _beforeInsert(Library\DatabaseContext $context)
    {
        return null;
    }

    protected function _beforeUpdate(Library\DatabaseContext $context)
    {
        // Set home.
        if ($this->isModified('home') && $this->home == 1)
        {
            $page = $this->getObject('com:pages.database.table.pages')
                ->select(array('home' => 1), Library\Database::FETCH_ROW);

            $page->home = 0;
            $page->save();
        }

        // Update child pages if menu has been changed.
        if ($this->isModified('pages_menu_id'))
        {
            $descendants = $this->getDescendants();

            foreach ($descendants as $descendant) {
                $descendant->setProperties(array('pages_menu_id' => $this->pages_menu_id))->save();
            }
        }
    }
}