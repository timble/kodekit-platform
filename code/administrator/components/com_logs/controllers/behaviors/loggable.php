<?php
/** $Id$ */

class ComLogsControllerBehaviorLoggable extends KControllerBehaviorAbstract
{
    protected $_title_column = '';
    protected $_actions;
    
    public function __construct(KConfig $config)
    { 
        parent::__construct($config);
        
        $this->_title_column = $config->title_column;
        
        $this->_actions = $config->actions->toArray();
        if (empty($this->_actions)) {
            $this->_actions = array('after.edit', 'after.add', 'after.delete');
        }
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority'   => KCommand::PRIORITY_LOW,
            'actions' => array(),
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
        } else {
            $rowset = KConfig::toData($context->result);
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

                KFactory::tmp('admin::com.logs.model.logs')
                    ->getItem()
                    ->setData($data)
                    ->save();
            }
        }
        
        return true;
    }
    
    public function getHandle()
    {
        return KMixinAbstract::getHandle();
    }
}