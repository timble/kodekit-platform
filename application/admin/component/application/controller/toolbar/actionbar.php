<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Toolbar Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class ApplicationControllerToolbarActionbar extends Library\ControllerToolbarActionbar
{
    public function getCommands()
    {
        $this->addCommand('profile');
        $this->addCommand('logout');

        return parent::getCommands();
    }

    protected function _commandProfile(Library\ControllerToolbarCommand $command)
    {
        $command->href = 'component=users&view=user&id='.$this->getController()->getUser()->getId();
    }

    protected function _commandLogout(Library\ControllerToolbarCommand $command)
    {
        $controller = $this->getController();
        $session    = $controller->getUser()->getSession();

        $url = 'component=users&view=session&id='.$session->getId();
        $url = $controller->getView()->getRoute($url);

        //Form configuration
        $config = "{method:'post', url:'".$url."', params:{_action:'delete', csrf_token:'".$session->getToken()."'}}";

        $command->append(array(
            'attribs' => array(
                'onclick'    => 'new Koowa.Form('.$config.').submit();',
                'data-action' => 'delete',
            )
        ));
    }
}