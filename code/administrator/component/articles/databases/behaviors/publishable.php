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

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_table           = $config->table;
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array('table'=> 'articles'));
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
        $query = $this->_getQuery();

        $query->where('publish_on <= :date')->where('published = :published')->where('publish_on IS NOT NULL')
            ->values('published = :value')
            ->bind(array('date'      => $date->format('Y-m-d H:i:s'),
                         'published' => 0,
                         'value'     => 1));

        $this->getMixer()->getAdapter()->update($query);
    }

    /**
     * Un-publishes items given a date.
     *
     * @param KDate $date The date on which items should be un-published.
     */
    protected function _unpublishItems(KDate $date)
    {
        $query = $this->_getQuery();

        $query->where('unpublish_on <= :date')->where('published = :published')->where('unpublish_on IS NOT NULL')
            ->values('published = :value')
            ->bind(array('date'      => $date->format('Y-m-d H:i:s'),
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
