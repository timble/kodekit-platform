<?php
/**
 * @version     $Id: default.php 2790 2011-01-12 03:06:29Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Default Model
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultModelDefault extends KModelDefault
{
/**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        // Set the static states
        $this->_state->limit = KFactory::get('joomla:application')->getCfg('list_limit');
    }
}