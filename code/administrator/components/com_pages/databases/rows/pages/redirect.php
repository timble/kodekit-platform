<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Redirect Page Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesDatabaseRowPageRedirect extends ComPagesDatabaseRowPageAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'properties' => array('type_description', 'type_title')
        ));

        parent::_initialize($config);
    }

    public function setProperty($property)
    {
        switch($property)
        {
            case 'type_description':
                $this->type_description = JText::_('Redirect');
                break;

            case 'type_title':
                $this->type_title = JText::_('Redirect');
                break;
        }

        return parent::setProperty($property);
    }

    public function save()
    {
        if($this->link_type) {
            $this->link_type == 'id' ? $this->link_url = null : $this->link_id = null;
        }

        return parent::save();
    }
}