<?php
/**
 * @package     Koowa_Controller
 * @subpackage  Behavior
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Lockable Controller Behavior Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Behavior
 */
class ControllerBehaviorLockable extends ControllerBehaviorAbstract
{
    /**
     * Constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('after.read'  , array($this, 'lockEntity'));
        $this->registerCallback('after.save'  , array($this, 'unlockEntity'));
        $this->registerCallback('after.cancel', array($this, 'unlockEntity'));
    }

    /**
     * Render a lock flash message if the row is locked
     *
     * @param   CommandContext	$context A command context object
     * @return 	void
     */
    protected function _afterControllerRead(CommandContext $context)
    {
        $row = $context->result;

        //Add the notice if the row is locked
        if($row->isLockable() && $row->locked())
        {
            $user = $this->getObject('com:users.database.row.user')
                ->set('id', $row->locked_by)
                ->load();

            $date    = $this->getView()->getTemplate()->getHelper('date')->humanize(array('date' => $row->locked_on));
            $message = \JText::sprintf('Locked by %s %s', $user->get('name'), $date);

            $context->user->addFlashMessage($message, 'notice');
        }
    }

    /**
     * Lock callback
     *
     * Only lock if the context contains a row object and the view layout is 'form'.
     *
     * @param   CommandContext  $context The active command context
     * @return  void
     */
    public function lockEntity(CommandContext $context)
    {
        if ($context->result instanceof DatabaseRowInterface)
        {
            $view = $this->getView();

            if ($view instanceof ViewTemplate)
            {
                if($this->getModel()->getState()->isUnique() && $context->result->isLockable()) {
                    $context->result->lock();
                }
            }
        }
    }

    /**
     * Unlock callback
     *
     * @param  CommandContext  $context    The active command context
     * @return void
     */
    public function unlockEntity(CommandContext $context)
    {
        if ($context->result instanceof DatabaseRowInterface && $context->result->isLockable()) {
            $context->result->unlock();
        }
    }
}