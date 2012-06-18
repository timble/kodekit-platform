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
 * RSS view class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesViewRss extends KViewAbstract
{
    protected $_feed;

    public function __construct(KConfig $config) {
        parent::__construct($config);

        $this->_feed = JDocument::getInstance('feed');

        $state = $this->getModel()->getState();

        // Remove pagination constraints.
        unset($state->limit);
        unset($state->offset);
    }

    public function display() {

        $document                  = JFactory::getDocument();
        $this->_feed->title       = $document->title;
        $this->_feed->description = $document->description;

        $this->output = $this->_feed->render();

        return parent::display();
    }
}