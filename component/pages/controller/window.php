<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Window Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
class ControllerWindow extends Library\ControllerView implements Library\ControllerModellable
{
    /**
     * Model object or identifier
     *
     * @var	string|object
     */
    protected $_model;

    /**
     * Get the controller model
     *
     * @throws	\UnexpectedValueException	If the model doesn't implement the ModelInterface
     * @return	Library\ModelInterface
     */
    public function getModel()
    {
        if(!$this->_model instanceof Library\ModelInterface)
        {
            $config = array(
                'decorators'     => array('lib:model.composite.decorator'),
                'state_defaults' => array(
                    'id' => $this->getObject('pages')->getActive()->id,
                )
            );

            $this->_model = $this->getObject('com:pages.model.pages', $config);
        }

        return $this->_model;
    }
}