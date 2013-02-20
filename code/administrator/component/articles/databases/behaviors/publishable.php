<?php
/**
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
     * @var string The name of the table containing the publishable items.
     */
    protected $_table;

    /**
     * @var KDate The current date.
     */
    protected $_date;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_table           = $config->table;
        $this->_date            = new KDate(array('timezone' => 'GMT'));
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array('table'=> 'articles'));
        parent::_initialize($config);
    }

    protected function _beforeTableSelect(KCommandContext $context)
    {
        if (!$this->_uptodate) {

            $this->_publishItems();
            $this->_unpublishItems();

            $this->_uptodate = true;
        }
    }

    protected function _beforeTableInsert(KCommandContext $context)
    {
        // Same as update.
        $this->_beforeTableUpdate($context);
    }

    protected function _beforeTableUpdate(KCommandContext $context)
    {
        $data = $context->data;

        if ($data->published && (strtotime($data->publish_on) > $this->_date->getTimestamp())) {
            // Un-publish the item.
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
        $query = $this->getService('koowa:database.query.update');

        $query->table(array($this->_table));

        return $query;
    }
}
