<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Publishable Database Behavior Class
 *
 * Auto publishes/un-publishes items.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesDatabaseBehaviorPublishable extends KDatabaseBehaviorAbstract
{
    /**
     * @var bool Variable for keeping track of the updated status of the items table. A value of true
     * indicates that items are already up to date, i.e. published and unpublished according with the
     * current timestamp.
     */
    protected $_uptodate = false;

    /**
     * @var string The name of the table containing the items.
     */
    protected $_table;

    /**
     * @var string The name of the identity column of the table.
     */
    protected $_identity_column;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_table           = $config->table;
        $this->_identity_column = $config->identity_column;
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array('table'=> 'articles', 'identity_column' => 'articles_article_id'));
        parent::_initialize($config);
    }

    protected function _beforeTableSelect(KCommandContext $context)
    {
        if (!$this->_uptodate) {

            $date = new KDate(array('timezone' => 'GMT'));

            $this->_publishItems($date);
            $this->_unpublishItems($date);

            $this->_uptodate = true;
        }
    }

    /**
     * Publishes items given a date.
     *
     * @param KDate $date The date on which items should be published.
     */
    protected function _publishItems(KDate $date)
    {
        $query = $this->_getSelectQuery();

        $query->where('publish_on <= :date')->where('published = :published')->where('publish_on <> :default')
            ->bind(array('date' => $date->format('Y-m-d H:i:s'), 'default' => '0000-00-00 00:00:00', 'published' => 0));

        $db = $this->getMixer()->getAdapter();

        if ($ids = $db->select($query, KDatabase::FETCH_ARRAY_LIST)) {
            foreach ($ids as $id) {
            	$this->_updateState($id, 1);
            }
        }
    }

    /**
     * Un-publishes items given a date.
     *
     * @param KDate $date The date on which items should be un-published.
     */
    protected function _unpublishItems(KDate $date)
    {
        $query = $this->_getSelectQuery();

        $query->where('unpublish_on <= :date')->where('published = :published')->where('unpublish_on <> :default')
            ->bind(array('date' => $date->format('Y-m-d H:i:s'), 'default' => '0000-00-00 00:00:00', 'published' => 1));

        $db = $this->getMixer()->getAdapter();

        if ($ids = $db->select($query)) {
        	foreach ($ids as $id) {
        		$this->_updateState($id, 0);
        	}
        }
    }

    /**
     * Generic select query getter.
     *
     * @return object A select query object.
     */
    protected function _getSelectQuery()
    {
        $query = $this->getService('koowa:database.query.select');

        $query->table(array($this->_table));
        $query->columns(array($this->_identity_column));

        return $query;
    }

    /**
     * Updates items states.
     *
     * @param     $ids   A list of items ids to be updated.
     * @param int $published The new published value.
     */
    protected function _updateState($id, $published = 0)
    {
        $query = $this->getService('koowa:database.query.update');

        $query->table($this->_table);

        $query->values(array('published = :published'))->bind(array(
            'published'   => $published));

        $query->where($this->_identity_column . ' IN :id')->bind(array('id' => (array) $id));

        $db = $this->getMixer()->getAdapter();
        $db->update($query);
    }
}
