<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Default View Controller
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComBaseControllerView extends Framework\ControllerView
{
    /**
     * Render action
     *
     * @param   Framework\CommandContext A command context object
     * @return  Framework\DatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionRender(Framework\CommandContext $context)
    {
        JFactory::getLanguage()->load($this->getIdentifier()->package);
        return parent::_actionRender($context);
    }
}