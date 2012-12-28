<?php
/**
 * @version     $Id: default.php 2939 2011-03-18 01:13:04Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Page Controller
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerView extends KControllerView
{
    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options.
     */
    protected function  _initialize(KConfig $config)
  	{
		$config->append(array(
		    'toolbars'  => array('menubar', $this->getIdentifier()->name),
		));

      	parent::_initialize($config);
  	}

    /**
     * Render action
     *
     * @param   KCommandContext A command context object
     * @return  KDatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionRender(KCommandContext $context)
    {
        JFactory::getLanguage()->load($this->getIdentifier()->package);
        return parent::_actionRender($context);
    }
}