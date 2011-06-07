<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Frontpage Articles Model Class
 *
 * @author      Richie Mortimer <http://nooku.assembla.com/profile/ravenlife>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 */
class ComFrontpageModelArticles extends KModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_state
            ->insert('section'   , 'int')
            ->insert('category'  , 'int')
            ->insert('state'     , 'int')
            ->insert('author'    , 'int')
            ->insert('access'    , 'int');
    }

    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
        $query->join('LEFT', 'content AS article', 'article.id = tbl.content_id')
              ->join('LEFT', 'categories AS category', 'category.id = article.catid')
              ->join('LEFT', 'sections AS section', 'section.id = category.section')
              ->join('LEFT', 'users AS user', 'user.id = article.created_by');

        parent::_buildQueryJoins($query);
    }

    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        parent::_buildQueryColumns($query);

        $query->select(array('article.title', 'article.state', 'article.access', 'article.sectionid',
                'article.catid', 'article.created_by'))
            ->select('category.title AS category_title')
            ->select('section.title AS section_title')
            ->select('user.name AS created_by_name');
    }

    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        $state = $this->_state;

        //Dont show trashed or archived items
        $query->where('article.state', '!=',  -1)
              ->where('article.state', '!=', -2);

        if($state->search) {
            $query->where('article.title', 'LIKE',  '%'.$state->search.'%');
        }

        if(is_numeric($state->state)) {
            $query->where('article.state', '=', $state->published);
        }

        if($state->section > -1) {
            $query->where('article.sectionid',  '=', $state->section);
        }

        if($state->category > -1) {
            $query->where('article.catid',  '=', $state->category);
        }

        if($state->author) {
            $query->where('article.created_by',  '=', $state->author);
        }

        if($state->access) {
            $query->where('article.access',  '=', $state->access);
        }

        parent::_buildQueryWhere($query);
    }
}