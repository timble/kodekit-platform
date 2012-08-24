<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Templates Model Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions   
 */
class ComExtensionsModelTemplates extends KModelAbstract
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('limit'      , 'int')
            ->insert('offset'     , 'int')
            ->insert('sort'       , 'cmd')
            ->insert('direction'  , 'word', 'asc')
            ->insert('application', 'cmd', 'site')
            ->insert('default'    , 'boolean', false, true)
            ->insert('name'       , 'cmd', null, true);        
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
            $state = $this->getState();

            //Get application path
            $path = $this->getIdentifier()->getApplication($state->application);
            
            if ($path)
            {                
                //Get default template
                $default = JComponentHelper::getParams('com_extensions')->get('template_'.$state->application, 'site');
             
                if ($state->default) {
			        $state->name = $default;
				}

                $data = array(
                	'path'        => $path.'/templates/'.$default,
                	'application' => $state->application
                );

                $row = $this->getService('com://admin/extensions.database.row.template', array('data' => $data));
                $row->default = ($row->name == $default);
                
                $this->_item = $row;
            }
            else throw new KModelException('Invalid application');
        }
        
        return $this->_item;
    }

    public function getTotal()
	{
		if (!$this->_total) {
			$this->getList();
		}

		return $this->_total;
	}
}