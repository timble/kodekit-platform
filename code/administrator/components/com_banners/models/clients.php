<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Banners Model Class - Clients    
 *
 * @author      Cristiano Cucco <cristiano.cucco at gmail dot com>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
class ComBannersModelClients extends ComDefaultModelDefault 
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->_state
            ->insert('search',      'string')
            ;
    }
    
    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        parent::_buildQueryColumns($query);
        
        $query
            ->select('bannerscount.tot AS banners')
            ;
    }
        
    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
        $query->join[] = array(
            'type' => 'LEFT',
            'table' => '(SELECT cid, COUNT(bid) AS tot FROM #__banner GROUP BY cid) AS bannerscount',
            'condition' => array('tbl.cid = bannerscount.cid')
        );
    }
    
    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        parent::_buildQueryWhere($query);
        
        $state = $this->_state;
        
        if (!empty($state->search)) {
            $query->where('LOWER(tbl.name)', 'LIKE', '%'.strtolower($state->search).'%');
        }
    }
    
    
    /**
     * Validation - is called by validate command for server side input validation
     * 
     * @param array $data
     * @return array $errors
     */
    public function validate($data)
    {
        $err = array();
        
        // name required
        $name = $data->get('name');
        if (empty($name)) {
            $err[] = JText::_('PLEASE FILL IN THE CLIENT NAME.');
        }
        // client required
        $contact = $data->get('contact');
        if (empty($contact)) {
            $err[] = JText::_('PLEASE FILL IN THE CONTACT NAME.');
        }
        // category required
        $email = $data->get('email');
        if (empty($email)) {
            $err[] = JText::_('PLEASE FILL IN THE CONTACT EMAIL.');
        }
        
        return $err;
    }
}