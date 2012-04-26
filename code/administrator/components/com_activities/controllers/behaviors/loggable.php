<?php
/**
 * @version		$Id: loggable.php 1545 2012-02-29 16:22:41Z arunasmazeika $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Activities
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
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
        $this->_title_column = KConfig::unbox($config->title_column);
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority'     => KCommand::PRIORITY_LOWEST,
            'actions'      => array('after.edit', 'after.add', 'after.delete'),
            'title_column' => array('title', 'name'),
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

                        if (is_array($this->_title_column))
                        {
                            foreach($this->_title_column as $title)
                            {
                                if($row->{$title}){
                                    $log['title'] = $row->{$title};
                                    break;
                                }
                            }
                        }
                        elseif($row->{$this->_title_column})
                        {
                            $log['title'] = $row->{$this->_title_column};
                        }

                        if (!isset($log['title'])) {
                            $log['title'] = '#'.$row->id;
                        }

                        $log['row'] = $row->id;
                        
                        $log['ip'] = KRequest::get('server.REMOTE_ADDR', 'raw');
                        
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