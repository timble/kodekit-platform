<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * User HTML View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
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