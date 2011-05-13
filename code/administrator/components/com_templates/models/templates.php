<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Templates Model Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates   
 */
class ComTemplatesModelTemplates extends KModelAbstract
{
    /**
     * Cached result of self::getDefaultTemplate
     *
     * @var string
     */
    protected $_default_template;

    /**
     * Constructor
     *A
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_state
            ->insert('application', 'cmd', 'site')
            ->insert('name'       , 'cmd', null, true)
            ->insert('limit'      , 'int')
            ->insert('offset'     , 'int')
            ->insert('sort'       , 'cmd')
            ->insert('direction'  , 'word', 'asc')
            ->insert('search'     , 'string');
    }

    /**
     * Method to get a item
     *
     * @return KDatabaseRowInterface
     */
    public function getItem()
    {
        if(!isset($this->_item))
        {
            //Templates without a manifest can't be parsed
            $base = $this->_state->application == 'admin' ? JPATH_ADMINISTRATOR : JPATH_SITE;
            
            $path = $base.'/templates/'.$this->_state->name;
            if(!file_exists($path.'/templateDetails.xml')) return $this->_item = null;

            $data = array(
                'path'        => $path,
                'name'        => $this->_state->name,
                'application' => $this->_state->application,
                'default'     => $this->_state->name == $this->getDefaultTemplate(),
            );

            $this->_item = KFactory::tmp('admin::com.templates.database.row.template', array('data' => $data));
        }
        
        return $this->_item;
    }

    /**
     * Get a list of items
     *
     * @return KDatabaseRowsetInterface
     */
    public function getList()
    { 
        if(!isset($this->_list))
        {
            $data = array();
            $base = $this->_state->application == 'admin' ? JPATH_ADMINISTRATOR : JPATH_SITE;
            $path = $base.'/templates';

            foreach(new DirectoryIterator($path) as $file)
            {
                if($file->isDir() && !($file->isDot() || in_array($file->getFilename(), array('.svn'))))
                {
                    //Apply states
                    if($this->_state->name && $this->_state->name != $file->getFilename())                     continue;
                    if($this->_state->search && strpos($file->getFilename(), $this->_state->search) === false) continue;
                    
                    //Templates without a manifest can't be parsed
                    if(!file_exists($file->getRealPath().'/templateDetails.xml')) continue;
                    
                    $data[] = array(
                        'path'        => $file->getRealPath(),
                        'name'        => $file->getFilename(),
                        'application' => $this->_state->application,
                        'default'     => $file->getFilename() == $this->getDefaultTemplate(),
                    );
                }
            }

            //Apply limit and offset
            if($this->_state->limit) {
                $data = array_slice($data, $this->_state->offset, $this->_state->limit);
            }

            $this->_list = KFactory::tmp('admin::com.templates.database.rowset.templates', array('data' => $data));
        }

        return $this->_list;
    }

    /**
     * Get the default template
     *
     * @return string     name of the template
     */
    public function getDefaultTemplate()
    {
        if(!isset($this->_default_template))
        {
            $this->_default_template = JComponentHelper::getParams('com_templates')->get($this->_state->application, 'site');
        }

        return $this->_default_template;
    }

    /**
     * Get the total amount of items
     *
     * @return  int
     */
    public function getTotal()
    {
        if(!isset($this->_total))
        {
            $this->_total = count($this->getList());
        }

        return $this->_total;
    }
}