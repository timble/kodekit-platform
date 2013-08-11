<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * User Html View
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Users
 */
class UsersViewUserHtml extends Library\ViewHtml
{
    public function render()
    {
        $this->parameters = $this->getObject('application')->getParams();
        $this->page = $this->getObject('application.pages')->getActive();

        //JFactory::getDocument()->setTitle($this->parameters->get('page_title'));
        return parent::render();
    }
}