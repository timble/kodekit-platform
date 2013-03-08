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
 * Date Helper
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultTemplateHelperBehavior extends Framework\TemplateHelperBehavior
{
    /**
     * Keep session alive
     *
     * This will send an ascynchronous request to the server via AJAX on an interval
     *
     * @return string   The html output
     */
    public function keepalive($config = array())
    {
        $session = $this->getService('user')->getSession();
        if($session->isActive())
        {
            //Get the config session lifetime
            $lifetime = $session->getLifetime() * 1000;

            //Refresh time is 1 minute less than the liftime
            $refresh =  ($lifetime <= 60000) ? 30000 : $lifetime - 60000;

            $config = new Framework\Config($config);
            $config->append(array(
                'refresh' => $refresh
            ));

            return parent::keepalive($config);
        }
    }
}