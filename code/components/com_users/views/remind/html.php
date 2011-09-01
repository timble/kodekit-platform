<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Reminder HTML View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersViewRemindHtml extends ComDefaultViewHtml
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'layout' => 'form'
        ));

        parent::_initialize($config);
    }

    public function display()
    {
        $this->parameters = $this->getParameters();

        KFactory::get('joomla:document')->setTitle($this->parameters->get('page_title'));
        return parent::display();
    }

    public function getParameters()
    {
        $parameters = KFactory::get('joomla:application')->getParams();
        $menu       = JSite::getMenu()->getActive();

        if(is_object($menu))
        {
			$menu_parameters = new JParameter($menu->params);
			if(!$menu_parameters->get('page_title')) {
				$parameters->set('page_title', JText::_('FORGOT_YOUR_USERNAME'));
			}
		}
		else $parameters->set('page_title', JText::_('FORGOT_YOUR_USERNAME'));

        return $parameters;
    }
}