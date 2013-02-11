<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Application Pathway Config
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationConfigPathway extends KConfig
{
    /**
     * Add item to pathway
     *
     * @param $name
     * @param null $link
     * @return ComApplicationConfigPathway
     */
    public function addItem($name, $link = null)
    {
        $item = new stdClass();
        $item->name = html_entity_decode($name, ENT_COMPAT, 'UTF-8');
        $item->link = $link;

        $this->_data['items'][] = $item;

        return $this;
    }
}