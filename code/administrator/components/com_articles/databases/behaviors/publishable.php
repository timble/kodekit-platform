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

    public function __construct(KConfig $config) {
        parent::__construct($config);

        $this->_table           = $config->table;
        $this->_identity_column = $config->identity_column;
    }

    protected function _initialize(KConfig $config) {
        $config->append(array('table'=> 'articles', 'identity_column' => 'articles_article_id'));
        parent::_initialize($config);
    }

    protected function _beforeTableSelect(KCommandContext $context) {

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
    protected function _publishItems(KDate $date) {
        $query = $this->_getSelectQuery();

        $query->where('publish_up <= :date')->where('state = :state')->where('publish_up <> :default')
            ->bind(array('date' => $date->format('Y-m-d H:i:s'), 'default' => '0000-00-00 00:00:00', 'state' => 0));

        $db = $this->getMixer()->getDatabase();

        if ($ids = $db->select($query, KDatabase::FETCH_ARRAY_LIST)) {
            $this->_updateState($ids, 1);
        }
    }

    /**
     * Un-publishes items given a date.
     *
     * @param KDate $date The date on which items should be un-published.
     */
    protected function _unpublishItems(KDate $date) {
        $query = $this->_getSelectQuery();

        $query->where('publish_down <= :date')->where('state = :state')->where('publish_down <> :default')
            ->bind(array('date' => $date->format('Y-m-d H:i:s'), 'default' => '0000-00-00 00:00:00', 'state' => 1));

        $db = $this->getMixer()->getDatabase();

        if ($ids = $db->select($query)) {
            $this->_updateState($ids, 0);
        }
    }

    /**
     * Generic select query getter.
     *
     * @return object A select query object.
     */
    protected function _getSelectQuery() {
        $query = $this->getService('koowa:database.query.select');

        $query->table(array($this->_table));
        $query->columns(array($this->_identity_column));

        return $query;
    }

    /**
     * Updates items states.
     *
     * @param     $ids   A list of items ids to be updated.
     * @param int $state The new state value.
     */
    protected function _updateState($ids, $state = 0) {
        $query = $this->getService('koowa:database.query.update');

        $query->table($this->_table);

        // Determine which column should be reset.
        $column = $state ? 'publish_up' : 'publish_down';
        $query->values(array('state = :state', $column . ' = :default'))->bind(array(
            'state'   => $state,
            'default' => '0000-00-00 00:00:00'));

        $query->where($this->_identity_column . ' IN :ids')->bind(array('ids' => (array) $ids));

        $db = $this->getMixer()->getDatabase();
        $db->update($query);
    }
}
