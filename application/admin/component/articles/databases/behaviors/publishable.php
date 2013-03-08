<?php
/**
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Framework;

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
     * Track updated status
     *
     * Variable keeps track of the updated status of the items table. A value of true indicates that items are
     * already up to date, i.e. published and unpublished according with the current timestamp.
     *
     * @var bool
     */
    protected $_uptodate = false;

    /**
     * The name of the table containing the publishable items.
     *
     * @var string
     */
    protected $_table;

    /**
     * The current date.
     *
     * @var Framework\Date The current date.
     */
    protected $_date;

    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);
        
        $this->_table = $config->table;
        $this->_date  = new Framework\Date(array('timezone' => 'GMT'));
    }

    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'table'=> 'articles'
        ));

        parent::_initialize($config);
    }

    protected function _afterTableSelect(Framework\CommandContext $context)
    {
        if (!$this->_uptodate) {

        if ($data instanceof KDatabaseRowsetInterface && !$this->_uptodate)
        {
            $this->_publishItems();
            $this->_unpublishItems();

            $this->_uptodate = true;
        }
    }

    protected function _beforeTableInsert(Framework\CommandContext $context)
    {
        // Same as update.
        $this->_beforeTableUpdate($context);
    }

    protected function _beforeTableUpdate(Framework\CommandContext $context)
    {
        $data = $context->data;

        // Un-publish the item
        if ($data->published && (strtotime($data->publish_on) > $this->_date->getTimestamp())) {
            $data->published = 0;
        }
    }

    /**
     * Publishes items given a date.
     */
    protected function _publishItems()
    {
        $query = $this->_getQuery();

        $query->where('publish_on <= :date')->where('published = :published')->where('publish_on IS NOT NULL')
            ->values('published = :value')
            ->bind(array('date'      => $this->_date->format('Y-m-d H:i:s'),
                         'published' => 0,
                         'value'     => 1));

        $this->getMixer()->getAdapter()->update($query);
    }

    /**
     * Un-publishes items given a date.
     */
    protected function _unpublishItems()
    {
        $query = $this->_getQuery();

        $query->where('unpublish_on <= :date')->where('published = :published')->where('unpublish_on IS NOT NULL')
            ->values('published = :value')
            ->bind(array('date'      => $this->_date->format('Y-m-d H:i:s'),
                         'published' => 1,
                         'value'     => 0));

        $this->getMixer()->getAdapter()->update($query);
    }

    /**
     * Generic query getter.
     *
     * @return object A query object.
     */
    protected function _getQuery()
    {
        $query = $this->getService('lib://nooku/database.query.update');
        $query->table(array($this->_table));

        return $query;
    }
}
