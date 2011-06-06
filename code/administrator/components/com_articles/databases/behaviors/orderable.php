<?php
class ComArticlesDatabaseBehaviorOrderable extends KDatabaseBehaviorOrderable
{
    public function _buildQueryWhere(KDatabaseQuery $query)
    {
        $query->where('catid', '=', $this->category_id)
            ->where('state', '>=', 0);
    }
}