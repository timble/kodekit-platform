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
 * Bootstrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Contacts
 */
 class ContactsBootstrapper extends Library\BootstrapperAbstract
{
    public function bootstrap()
    {
        $manager = $this->getObjectManager();
        $manager->registerAlias('com:contacts.model.categories', 'com:categories.model.categories');
    }
}