<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Controller Toolbar
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
abstract class ControllerToolbarDecorator extends ObjectDecorator implements ControllerToolbarInterface, CommandInterface
{
    /**
     * Command handler
     *
     * This function translates the command name to a command handler function of the format '_beforeController[Command]'
     * or '_afterController[Command]. Command handler functions should be declared protected.
     *
     * @param 	string           $name	    The command name
     * @param 	CommandContext  $context 	The command context
     * @return 	boolean Always returns TRUE
     */
    final public function execute($name, CommandContext $context)
    {
        $identifier = clone $context->getSubject()->getIdentifier();
        $type = array_shift($identifier->path);

        $parts  = explode('.', $name);
        $method = '_'.$parts[0].ucfirst($type).ucfirst($parts[1]);

        if(method_exists($this, $method)) {
            $this->$method($context);
        } else {
            $this->getDelegate()->execute($name, $context);
        }

        return true;
    }

    /**
     * Decorate Notifier
     *
     * Automatically attach the decorate toolbar if the delegate has previously already been attached. This will
     * subscribe the decorator to the event dispatcher.
     *
     * @param object $delegate The object being decorated
     * @return void
     * @throws  \InvalidArgumentException If the delegate is not an object
     * @see ControllerToolbarMixin::attachToolbar()
     */
    public function onDecorate($delegate)
    {
        $controller = $delegate->getController();

        if ($controller->inherits('Nooku\Library\ControllerToolbarMixin'))
        {
            if($controller->hasToolbar($delegate->getType()))
            {
                $controller->detachToolbar($delegate);
                $controller->attachToolbar($this);
            }
        }

        parent::onDecorate($delegate);
    }

    /**
     * Get the toolbar's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getIdentifier()->name;
    }

    /**
     * Add a command
     *
     * @param   string    $command The command name
     * @param   mixed    $config  Parameters to be passed to the command
     * @return  ControllerToolbarCommand  The command that was added
     */
    public function addCommand($command, $config = array())
    {
        return $this->getDelegate()->addCommand($command, $config);
    }

    /**
     * Get a command by name
     *
     * @param string $name  The command name
     * @param array $config An optional associative array of configuration settings
     * @return mixed ControllerToolbarCommand if found, false otherwise.
     */
    public function getCommand($name, $config = array())
    {
        if(!$this->getDelegate()->hasCommand($name))
        {
            //Create the config object
            $command = new ControllerToolbarCommand($name, $config);

            //Attach the command to the toolbar
            $command->setToolbar($this);

            //Find the command function to call
            if (method_exists($this, '_command' . ucfirst($name)))
            {
                $function = '_command' . ucfirst($name);
                $this->$function($command);
            }
            else $this->getDelegate()->getCommand($name, $config);

        }
        else $command = $this->getDelegate()->getCommand($name);

        return $command;
    }

    /**
     * Check if a command exists
     *
     * @param string $name  The command name
     * @return boolean True if the command exists, false otherwise.
     */
    public function hasCommand($name)
    {
        return $this->getDelegate()->hasCommand($name);
    }

    /**
     * Get the list of commands
     *
     * @return  array
     */
    public function getCommands()
    {
        return $this->getDelegate()->getCommands();
    }

    /**
     * Get the priority of the delegate
     *
     * @return	integer The event priority
     */
    public function getPriority()
    {
        return $this->getDelegate()->getPriority();
    }

    /**
     * Get a new iterator
     *
     * @return  \RecursiveArrayIterator
     */
    public function getIterator()
    {
        return $this->getDelegate()->getIterator();
    }

    /**
     * Returns the number of toolbar commands
     *
     * Required by the Countable interface
     *
     * @return int
     */
    public function count()
    {
        return $this->getDelegate()->count();
    }
}