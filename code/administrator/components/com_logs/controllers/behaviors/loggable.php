<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Logs
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Log Behavior
 *
 * @author      Israel Canasa <israel@timble.net>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Logs
 */

class ComLogsControllerBehaviorLoggable extends KControllerBehaviorAbstract
{
    protected $_actions;
    protected $_title_column;

    public function __construct(KConfig $config)
    { 
        parent::__construct($config);
        
        $this->_actions = $config->actions->toArray();
        $this->_title_column = $config->title_column;
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority'   => KCommand::PRIORITY_LOWEST,
            'actions' => array('after.edit', 'after.add', 'after.delete'),
            'title_column' => 'title',
        ));

        parent::_initialize($config);
    }
    
    public function execute($name, KCommandContext $context)
    {
        if(!in_array($name, $this->_actions))
            return;

        $identifier = $context->caller->getIdentifier();
                
        $data = array(
            'action' => $context->action,
            'application' => $identifier->application,
            'type' => $identifier->type,
            'package' => $identifier->package,
            'name' => $identifier->name,
        );

        $rowset = array();
        if ($context->result instanceof KDatabaseRowAbstract) {
            $rowset[] =  $context->result;
        } elseif($context->result instanceof KDatabaseRowsetAbstract) {
            $rowset = $context->result;
        }else{
            return false;      
        }

        foreach ($rowset as $row)
        {
            //Only log if the row status is valid.
            $status = $row->getStatus();
            
            if(!empty($status))
            {
                if ($row->{$this->_title_column}) {
                    $data['title'] = $row->{$this->_title_column};
                } else {
                    $data['title'] = '#'.$row->id;
                }

                $data['row_id'] = $row->id;

                $this->getService('com://admin/logs.model.logs')
                    ->getItem()
                    ->setData($data)
                    ->save();
            }
        }
    }
    
    public function getHandle()
    {
        return KMixinAbstract::getHandle();
    }
}