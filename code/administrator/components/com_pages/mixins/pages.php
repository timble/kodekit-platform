<?php
class ComPagesMixinPages extends KMixinAbstract
{
    protected $_active;
    protected $_home;
    
    public function setRoute()
    {
        $pages = $this->getData();
        foreach($this->getMixer() as $page)
        {
            $path = array();
            foreach(explode('/', $page->path) as $id) {
                $path[] = $pages[$id]['slug'];
            }

            $page->route = implode('/', $path);
        }
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

    public function getHome()
    {
        if(!isset($this->_home)) {
            $this->_home = $this->find(array('home' => 1))->top();
        }

        return $this->_home;
    }

    public function isAuthorized($id, $accessid = 0)
    {
        $page = $this->find($id);

        return $page->access <= $accessid;
    }
}