<?php
class ComArticlesDatabaseBehaviorCreatable extends KDatabaseBehaviorCreatable
{
    protected function _beforeTableUpdate(KCommandContext $context)
    {
        if(isset($this->_modified['created_by'])) {
            $this->created_by  = (int) KFactory::get('lib.joomla.user')->get('id');
        }
    }
}