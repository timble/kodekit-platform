<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Activities
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Loggable Controller Behavior Class
 *
 * @author      Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Activities
 */

class ComActivitiesControllerBehaviorLoggable extends KControllerBehaviorAbstract
{
    /**
     * List of actions to log
     * 
     * @var array
     */
    protected $_actions;
    
    /**
     * The name of the column to use as the title column in the log entry
     * 
     * @var string
     */
    protected $_title_column;

    public function __construct(KConfig $config)
    { 
        parent::__construct($config);
        
        $this->_actions      = KConfig::unbox($config->actions);
        $this->_title_column = $config->title_column;
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority'     => KCommand::PRIORITY_LOWEST,
            'actions'      => array('after.edit', 'after.add', 'after.delete'),
            'title_column' => 'title',
        ));

        parent::_initialize($config);
    }
    
    public function execute($name, KCommandContext $context)
    {
        if(in_array($name, $this->_actions))
        {
            $data = $context->result;
            
            if($data instanceof KDatabaseRowAbstract || $data instanceof KDatabaseRowsetAbstract )
            {
                $rowset = array(); 
                
                if ($data instanceof KDatabaseRowAbstract) {
                    $rowset[] = $data;
                } else {
                    $rowset = $data;
                }
            
                foreach ($rowset as $row)
                {
                    //Only log if the row status is valid.
                    $status = $row->getStatus();
                    
                    if(!empty($status))
                    {
                         $identifier = $context->caller->getIdentifier();
                 
                         $log = array(
                            'action'	  => $context->action,
            				'application' => $identifier->application,
            				'type'        => $identifier->type,
            				'package'     => $identifier->package,
            				'name'        => $identifier->name,
                    		'status'      => $status
                        );

                        if (!empty($row->created_by)) {
                            $log['created_by'] = $row->created_by;
                        }
                
                        if ($row->{$this->_title_column}) {
                            $log['title'] = $row->{$this->_title_column};
                        } else {
                            $log['title'] = '#'.$row->id;
                        }

                        $log['row'] = $row->id;

                        $this->getService('com://admin/activities.database.row.activity', array('data' => $log))->save();
                    }
                }
            }
        }
    }
    
    public function getHandle()
    {
        return KMixinAbstract::getHandle();
    }
}