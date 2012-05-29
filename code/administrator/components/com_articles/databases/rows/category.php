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
 * Category database row class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesDatabaseRowCategory extends KDatabaseRowDefault
{
    /**
     * Returns the total amount of articles of the category.
     *
     * @param array $config An optional configuration array.
     *
     * @return int|null The number of articles, null if row is new.
     */
    public function getTotalArticles($config = array()) {

        $config = new KConfig($config);

        $config->append(array('model_state' => array('category' => $this->id)));

        $result = null;

        if (!$this->isNew()) {
            $result = $this->getService('com://admin/articles.model.articles')->set($config->model_state)->getTotal();
        }
        return $result;
    }

    /**
     * Returns the articles of the category.
     *
     * @param array $config An optional configuration array.
     *
     * @return KDatabaseRowsetDefault|null The articles, null if row is new.
     */
    public function getArticles($config = array()) {
        $config = new KConfig($config);

        $config->append(array('model_state' => array('category' => $this->id)));

        $result = null;

        if (!$this->isNew()) {
            $result = $this->getService('com://admin/articles.model.articles')->set($config->model_state)->getList();
        }
        return $result;
    }
}