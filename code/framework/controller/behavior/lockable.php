<?php
/**
 * @version     $Id$
 * @package     Koowa_Controller
 * @subpackage  Behavior
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Lockable Controller Behavior Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Behavior
 */
class KControllerBehaviorLockable extends KControllerBehaviorAbstract
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('after.read'  , array($this, 'lockEntity'));
        $this->registerCallback('after.save'  , array($this, 'unlockEntity'));
        $this->registerCallback('after.cancel', array($this, 'unlockEntity'));
    }

    /**
     * Lock callback
     *
     * Only lock if the context contains a row object and the view layout is 'form'.
     *
     * @param   KCommandContext  $context The active command context
     * @return  void
     */
    public function lockEntity(KCommandContext $context)
    {
        if ($context->result instanceof KDatabaseRowInterface)
        {
            $view = $this->getView();

            if ($view instanceof KViewTemplate)
            {
                if ($view->getLayout() == 'form' && $context->result->isLockable()) {
                    $context->result->lock();
                }
            }
        }
    }

    /**
     * Unlock callback
     *
     * @param  KCommandContext  $context    The active command context
     * @return void
     */
    public function unlockEntity(KCommandContext $context)
    {
        if ($context->result instanceof KDatabaseRowInterface && $context->result->isLockable()) {
            $context->result->unlock();
        }
    }
}