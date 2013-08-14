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
 * Listbox Template Helper
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Contacts
 */
class ContactsTemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function contacts($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'model' => 'contacts',
            'value'	=> 'id',
            'label'	=> 'name'
        ));

        return parent::_render($config);
    }
}