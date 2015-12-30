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
 * Pages Composite Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
class ModelCompositePages extends ModelPages implements Library\ObjectSingleton
{
    /**
     * The active page
     *
     * @var ModelEntityPage
     */
    protected $_active;

    /**
     * The default page
     *
     * @var ModelEntityPage
     */
    protected $_default;

    /**
     * The active pathway
     *
     * @var array
     */
    protected $_pathway;

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'decorators'     => array('lib:model.composite.decorator'),
            'state_defaults' => array(
                'enabled'     => true,
                'application' => APPLICATION_NAME,
            )
        ));

        parent::_initialize($config);
    }

    public function getPage($id)
    {
        return $this->fetch()->find($id);
    }

    public function setActive($active)
    {
        if(is_numeric($active)) {
            $this->_active = $this->getPage($active);
        } else {
            $this->_active = $active;
        }

        return $this;
    }

    public function getActive()
    {
        return $this->_active;
    }

    public function isActive(ModelEntityPage $page)
    {
        return (bool) ($page->id == $this->getActive()->id);
    }

    public function getDefault()
    {
        if(!isset($this->_default)) {
            $this->_default = $this->fetch()->find(array('default' => 1));
        }

        return $this->_default;
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
}