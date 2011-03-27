<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Command
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Command Chain
 * 
 * The command queue implements a double linked list. The command handle is used 
 * as the key. Each command can have a priority, default priority is 3 The queue 
 * is ordered by priority, commands with a higher priority are called first.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Command
 */
class KCommandChain extends KObjectQueue
{ 
    /**
     * Enabled status of the chain
     * 
     * @var boolean
     */
    protected $_enabled = true;
    
    /**
     * The chain's break condition
     * 
     * @see run()
     * @var boolean
     */
    protected $_break_condition = false;
    
    /**
     * The command context object
     * 
     * @var KCommandContext
     */
    protected $_context = null;

    /**
     * Constructor
     *
     * @return  void
     */
    public function __construct(KConfig $config = null)
    {
         //If no config is passed create it
        if(!isset($config)) $config = new KConfig();
        
        parent::__construct($config);
        
        $this->_break_condition = (boolean) $config->break_condition;
        $this->_enabled         = (boolean) $config->enabled;
        $this->_context         = $config->context;
    }
    
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'context'         =>  new KCommandContext(),
            'enabled'         =>  true,
            'break_condition' =>  false,
        ));
        
        parent::_initialize($config);
    }
    
    /**
     * Attach a command to the chain
     * 
     * The priority parameter can be used to override the command priority with 
     * enqueing the command.
     * 
     * @param   object      A KCommand object
     * @param   integer     The command priority, usually between 1 (high priority) and 5 (lowest), 
     *                      default is 3. If no priority is set, the command priority will be used 
     *                      instead.
     * @return   KCommandChain
     */
    public function enqueue( KCommandInterface $cmd, $priority = null)
    {
        $priority =  is_int($priority) ? $priority : $cmd->getPriority();
        return parent::enqueue($cmd, $priority);
    }
      
    /**
     * Run the commands in the chain
     * 
     * If a command returns the 'break condition' the executing is halted.
     * 
     * @param   string  The command name
     * @param   mixed   The command context
     * @return  void|boolean    If the chain is broken, returns the break condition. Default returns void.
     */
    public function run( $name, KCommandContext $context )
    {
        if($this->_enabled)
        {
            foreach($this as $command) 
            {
                if ( $command->execute( $name, $context ) === $this->_break_condition) {
                    return $this->_break_condition;
                }
            }
        }
    }
    
    /**
     * Enable the chain
     *
     * @return  void
     */
    public function enable()
    {
        $this->_enabled = true;
        return $this;
    }
    
    /**
     * Disable the chain
     * 
     * If the chain is disabled running the chain will always return TRUE
     *
     * @return  void
     */
    public function disable()
    {
        $this->_enabled = false;
        return $this;
    }
    
    /**
     * Set the priority of a command
     * 
     * @param object    A KCommand object 
     * @param integer   The command priority
     * @return KCommandChain
     */
    public function setPriority(KCommandInterface $cmd, $priority)
    {
       return parent::setPriority($cmd, $priority);
    }
    
    /**
     * Get the priority of a command
     * 
     * @param object    A KCommand object
     * @param integer   The command priority
     * @return  integer The command priority
     */
    public function getPriority(KCommandInterface $cmd)
    {
        return parent::getPriority($cmd);
    }
    
    /**
     * Factory method for a command context.
     * 
     * @return  KCommandContext
     */
    public function getContext()
    {   
        return clone $this->_context;
    }
}