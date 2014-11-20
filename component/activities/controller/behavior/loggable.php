<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Loggable Controller Behavior
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Nooku\Component\Activities
 */
class ControllerBehaviorLoggable extends Library\ControllerBehaviorAbstract
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

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_actions      = Library\ObjectConfig::unbox($config->actions);
        $this->_title_column = Library\ObjectConfig::unbox($config->title_column);
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority'     => self::PRIORITY_LOWEST,
            'actions'      => array('after.edit', 'after.add', 'after.delete'),
            'title_column' => array('title', 'name'),
        ));

        parent::_initialize($config);
    }

    public function execute(Library\CommandInterface $command, Library\CommandChainInterface $chain)
    {
        $name = $command->getName();

        if(in_array($name, $this->_actions))
        {
            $entities = $command->result;

            if($entities instanceof Library\ModelEntityInterface)
            {
                foreach ($entities as $entity)
                {
                    //Only log if the row status is valid.
                    $status = $entity->getStatus();

                    if(!empty($status))
                    {
                         $identifier = $command->getSubject()->getIdentifier();

                         $log = array(
                            'action'	  => $command->action,
            				'package'     => $identifier->package,
            				'name'        => $identifier->name,
                    		'status'      => $status,
                            'created_by'  => $command->user->getId()
                        );

                        if (is_array($this->_title_column))
                        {
                            foreach($this->_title_column as $title)
                            {
                                if($entity->{$title})
                                {
                                    $log['title'] = $entity->{$title};
                                    break;
                                }
                            }
                        }
                        elseif($entity->{$this->_title_column}) {
                            $log['title'] = $entity->{$this->_title_column};
                        }

                        if (!isset($log['title'])) {
                            $log['title'] = '#'.$entity->id;
                        }

                        $log['row'] = $entity->id;
                        $log['ip']  = $command->request->getAddress();

                        $this->getObject('com:activities.model.entity.activity', array('data' => $log))->save();
                    }
                }
            }
        }
    }

    public function getHandle()
    {
        return Library\ObjectMixinAbstract::getHandle();
    }
}