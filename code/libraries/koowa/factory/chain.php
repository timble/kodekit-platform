<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage	Chain
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Factory Command Chain
 *
 * The factory chain overrides the run method to be able to halt the chain
 * when a command return a value. If the command returns false the chain
 * will keep running.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Factory
 */
class KFactoryChain extends KCommandChain
{
    /**
     * Run the commands in the chain
     *
     * If a command returns not false the exection is halted
     *
     * @param string  The command name
     * @param object  The command context
     * @return object|false  Return object on success, returns FALSE on failure
     */
    final public function run( $identifier, KCommandContext $context )
    {   
        foreach($this as $command)
        {
            $result = $command->execute( $identifier, $context );
            if ($result !== false) {
                return $result;
            }
        }

        return false;
    }
}