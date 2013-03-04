<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

/**
 * Loggable Controller Behavior
 *
 * @author  Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Activities
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
            $entity = $context->result;

            if($entity instanceof KDatabaseRowInterface || $entity instanceof KDatabaseRowsetInterface )
            {
                $rowset = array();

                if ($entity instanceof KDatabaseRowInterface) {
                    $rowset[] = $entity;
                } else {
                    $rowset = $entity;
                }

                foreach ($rowset as $row)
                {
                    //Only log if the row status is valid.
                    $status = $row->getStatus();

                    if(!empty($status))
                    {
                         $identifier = $context->getSubject()->getIdentifier();

                         $log = array(
                            'action'	  => $context->action,
            				'application' => $identifier->namespace,
            				'type'        => $identifier->type,
            				'package'     => $identifier->package,
            				'name'        => $identifier->name,
                    		'status'      => $status
                        );

                        if (!empty($row->created_by)) {
                            $log['created_by'] = $row->created_by;
                        } else {
                            $log['created_by'] = $context->user->getId();
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
                        $log['ip']  = $context->request->getAddress();


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