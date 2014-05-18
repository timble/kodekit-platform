<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Abstract Typable Database Behavior
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Pages
 */
abstract class DatabaseBehaviorTypeAbstract extends Library\DatabaseBehaviorAbstract implements DatabaseBehaviorTypeInterface
{
    abstract function getTypeTitle();

    abstract function getTypeDescription();

    public function getParams($group)
    {
        return null;
    }

    public function getLink()
    {
        return null;
    }

    protected function _beforeTableInsert(Library\CommandContext $context)
    {
        return null;
    }

    protected function _beforeTableUpdate(Library\CommandContext $context)
    {
        // Set home.
        if($this->isModified('home') && $this->home == 1)
        {
            $page = $this->getObject('com:pages.database.table.pages')
                ->select(array('home' => 1), Library\Database::FETCH_ROW);

            $page->home = 0;
            $page->save();
        }

        // Update child pages if menu has been changed.
        if($this->isModified('pages_menu_id'))
        {
            $descendants = $this->getDescendants();
            if(count($descendants)) {
                $descendants->setData(array('pages_menu_id' => $this->pages_menu_id))->save();
            }
        }
    }
}