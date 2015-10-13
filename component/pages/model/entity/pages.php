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

    public function getPrimary()
    {
        if(!isset($this->_primary)) {
            $this->_primary = $this->find(array('home' => 1));
        }

        return $this->_primary;
    }

    public function isAuthorized($id, Library\UserInterface $user)
    {
        $result = true;
        $page   = $this->find($id);

        // Return false if page not found.
        if(!is_null($page))
        {
            if($page->access || $page->users_group_id > 0)
            {
                // Return false if page has access set, but user is a guest.
                if($user->isAuthentic())
                {
                    // Return false if page has group set, but user is not in that group.
                    if($page->users_group_id && !in_array($page->users_group_id, $user->getGroups()))
                    {
                        $result = false;
                    }
                }
                else $result = false;
            }
        }
        else $result = false;

        return $result;
    }
}
