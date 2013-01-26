<?php
abstract class ComPagesDatabaseBehaviorTypeAbstract extends KDatabaseBehaviorAbstract implements ComPagesDatabaseBehaviorTypeInterface
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

    protected function _beforeTableInsert(KCommandContext $context)
    {
        return null;
    }

    protected function _beforeTableUpdate(KCommandContext $context)
    {
        return null;
    }
}