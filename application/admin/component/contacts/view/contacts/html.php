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
 * Contacts HTML view class.
 *
 * @author     Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Contacts
 */
class ContactsViewContactsHtml extends Library\ViewHtml
{
    public function render()
    {        
        $state = $this->getModel()->getState();
        
        // Enable sortable
        $this->sortable = $state->category && $state->sort == 'ordering' && $state->direction == 'asc';
        
        return parent::render();
    }
}