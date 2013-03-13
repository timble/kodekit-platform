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
 * Page Controller
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class BaseControllerView extends Framework\ControllerView
{
    /**
     * Constructor.
     *
     * @param   object  An optional Framework\Config object with configuration options.
     */
    protected function  _initialize(Framework\Config $config)
  	{
        $config->append(array(
		    'toolbars'  => array('menubar', $this->getIdentifier()->name),
        ));

      	parent::_initialize($config);
  	}
}