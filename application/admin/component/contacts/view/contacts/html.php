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
 * Contacts HTML View
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Component\Contacts
 */
class ContactsViewContactsHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        // Enable sortable
        $state = $this->getModel()->getState();
        $context->data->sortable = $state->category && $state->sort == 'ordering' && $state->direction == 'asc';

        parent::_fetchData($context);
    }
}