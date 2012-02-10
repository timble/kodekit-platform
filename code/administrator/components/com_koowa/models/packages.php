<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Koowa
 * @copyright   Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Description
 *
 * @author      Stian Didriksen <stian@ninjaforge.com>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Koowa
 */
class ComKoowaModelPackages extends KModelAbstract
{
    /**
     * Constructor
     *
     * @TODO need to find a better filter for internal paths, as using dirname will move up the folder for each request for some odd reason
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_state
                        ->insert('folder', 'string', false)
                        ->insert('file', 'filename', false)
                        //@TODO limit state is just to avoid an error thrown in com.default.controller.default.action.browse
                        ->insert('limit', 'int', 10)
                        ->insert('directory', 'string', JPATH_COMPONENT_ADMINISTRATOR.'/packages/');
                        //->insert('directory', 'dirname', JPATH_COMPONENT_ADMINISTRATOR.'/packages/');
    }

    /**
     * Get a package
     *
     * @return  object
     */
    public function getItem()
    {
        if(!isset($this->_item))
        {
            $this->_item = $this->_state->file;
            if($this->_state->folder) $this->_item = $this->_state->folder;
        }
    
        return parent::getItem();
    }

    /**
     * Get packages
     *
     * @return  object
     */
    public function getList()
    {
        if(!isset($this->_list))
        {
            $this->_list  = JFolder::files($this->_state->directory, '^[A-Za-z0-9]', false, false);
            $this->_total = count($this->_list);
        }
        
        return parent::getList();
    }
}