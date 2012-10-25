<?php
/**
 * @version        $Id: pathway.php 14401 2010-01-26 14:10:00Z louis $
 * @package        Joomla.Framework
 * @subpackage    Application
 * @copyright    Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Class to manage the site application pathway
 *
 * @package     Joomla
 * @since        1.5
 */
class JPathway extends KObject
{
    /**
     * Array to hold the pathway item objects
     * @access private
     */
    var $_pathway = null;

    /**
     * Integer number of items in the pathway
     * @access private
     */
    var $_count = 0;

    /**
     * Class constructor
     */
    function __construct($options = array())
    {
        //Initialise the array
        $this->_pathway = array();
        $pages = KService::get('application.pages');

        if ($active = $pages->getActive())
        {
            $home = $pages->getHome();
            if (is_object($home) && ($active->id != $home->id))
            {
                foreach (explode('/', $active->path) as $id)
                {
                    $page = $pages->getPage($id);
                    $url = '';
                    switch ($page->type) {
                        case 'pagelink' :
                        case 'url' :
                            $url = $page->link;
                            break;
                        case 'separator' :
                            $url = null;
                            break;
                        default      :
                            {
                                $url = $page->link;
                                $url->query['Itemid'] = $page->id;

                                $url = KService::get('application')->getRouter()->build($url);
                            }
                    }

                    $this->addItem($page->title, $url);
                }
            }
        }
    }

    /**
     * Return the JPathWay items array
     *
     * @access public
     * @return array Array of pathway items
     * @since 1.5
     */
    function getPathway()
    {
        $pw = $this->_pathway;

        // Use array_values to reset the array keys numerically
        return array_values($pw);
    }

    /**
     * Set the JPathway items array.
     *
     * @access    public
     * @param    array    $pathway    An array of pathway objects.
     * @return    array    The previous pathway data.
     * @since    1.5
     */
    function setPathway($pathway)
    {
        $oldPathway = $this->_pathway;
        $pathway = (array)$pathway;

        // Set the new pathway.
        $this->_pathway = array_values($pathway);

        return array_values($oldPathway);
    }

    /**
     * Create and return an array of the pathway names.
     *
     * @access public
     * @return array Array of names of pathway items
     * @since 1.5
     */
    function getPathwayNames()
    {
        // Initialize variables
        $names = array(null);

        // Build the names array using just the names of each pathway item
        foreach ($this->_pathway as $item) {
            $names[] = $item->name;
        }

        //Use array_values to reset the array keys numerically
        return array_values($names);
    }

    /**
     * Create and add an item to the pathway.
     *
     * @access public
     * @param string $name
     * @param string $link
     * @return boolean True on success
     * @since 1.5
     */
    function addItem($name, $link = '')
    {
        // Initalize variables
        $ret = false;

        if ($this->_pathway[] = $this->_makeItem($name, $link)) {
            $ret = true;
            $this->_count++;
        }

        return $ret;
    }

    /**
     * Set item name.
     *
     * @access public
     * @param integer $id
     * @param string $name
     * @return boolean True on success
     * @since 1.5
     */
    function setItemName($id, $name)
    {
        // Initalize variables
        $ret = false;

        if (isset($this->_pathway[$id])) {
            $this->_pathway[$id]->name = $name;
            $ret = true;
        }

        return $ret;
    }

    /**
     * Create and return a new pathway object.
     *
     * @access private
     * @param string $name Name of the item
     * @param string $link Link to the item
     * @return object Pathway item object
     * @since 1.5
     */
    function _makeItem($name, $link)
    {
        $item = new stdClass();
        if ((version_compare(phpversion(), '5.0') < 0)) {
            $item->name = html_entity_decode($name);
        } else {
            $item->name = html_entity_decode($name, ENT_COMPAT, 'UTF-8');
        }
        $item->link = $link;

        return $item;
    }
}