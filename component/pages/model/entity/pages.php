<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Pages Model Entity
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class ModelEntityPages extends Library\ModelEntityRowset
{
    /**
     * The active page
     *
     * @var ModelEntityPage
     */
    protected $_active;

    /**
     * The primary language
     *
     * @var ModelEntityPage
     */
    protected $_primary;

    /**
     * The pathway
     *
     * @var array
     */
    protected $_pathway;

    public function __construct(Library\ObjectConfig $config )
    {
        parent::__construct($config);

        //Calculate the page routes
        foreach($this as $page)
        {
            $path = array();
            foreach(explode('/', $page->path) as $id) {
                $path[] = $this->find($id)->slug;
            }

            $page->route = implode('/', $path);
        }
    }

    public function find($needle)
    {
        $result = null;

        if(is_array($needle) && array_key_exists('link', $needle) && is_array($needle['link']))
        {
            $query = $needle['link'];
            unset($needle['link']);

            $pages  = parent::find($needle);
            $result = null;

            foreach($pages as $page)
            {
                foreach($query as $parts)
                {
                    $result = $page;
                    foreach($parts as $key => $value)
                    {
                        if(!(isset($page->getLink()->query[$key]) && $page->getLink()->query[$key] == $value))
                        {
                            $result = null;
                            break;
                        }
                    }

                    if(!is_null($result)) {
                        break(2);
                    }
                }
            }
        }
        else $result = parent::find($needle);

        return $result;
    }

    public function getPage($id)
    {
        $page = $this->find($id);
        return $page;
    }

    public function getPrimary()
    {
        if(!isset($this->_primary)) {
            $this->_primary = $this->find(array('home' => 1));
        }

        return $this->_primary;
    }

    public function getPathway()
    {
        if(!isset($this->_pathway))
        {
            $this->_pathway = new \ArrayObject();

            foreach(explode('/', $this->getActive()->path) as $id)
            {
                $page = $this->getPage($id);

                $this->_pathway[] = array(
                    'title' => $page->title,
                    'link'  => $page->getLink()
                );
            }
        }

        return $this->_pathway;
    }

    public function setActive($active)
    {
        if(is_numeric($active)) {
            $this->_active = $this->find($active);
        } else {
            $this->_active = $active;
        }

        return $this;
    }

    public function getActive()
    {
        return $this->_active;
    }

}
