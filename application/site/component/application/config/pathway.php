<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Pathway Config
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Component\Application
 */
class ApplicationConfigPathway extends Library\ObjectConfig
{
    /**
     * Add item to pathway
     *
     * @param $name
     * @param null $link
     * @return ApplicationConfigPathway
     */
    public function addItem($name, $link = null)
    {
        $item = new \stdClass();
        $item->name = html_entity_decode($name, ENT_COMPAT, 'UTF-8');
        $item->link = $link;

        $this->append(array('items' => array($item)));

        return $this;
    }
}