<?php

use Nooku\Framework;

abstract class ComPagesDatabaseBehaviorTypeAbstract extends Framework\DatabaseBehaviorAbstract implements ComPagesDatabaseBehaviorTypeInterface
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

    protected function _beforeTableInsert(Framework\CommandContext $context)
    {
        return null;
    }

    protected function _beforeTableUpdate(Framework\CommandContext $context)
    {
        // Set home.
        if($this->isModified('home') && $this->home == 1)
        {
            $page = $this->getService('com://admin/pages.database.table.pages')
                ->select(array('home' => 1), Framework\Database::FETCH_ROW);

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