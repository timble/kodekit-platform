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
 * @package Component\Articles
 */
 class ArticlesBootstrapper extends Library\BootstrapperAbstract
{
    public function bootstrap()
    {
        $manager = $this->getObjectManager();

        $manager->registerAlias('com:articles.model.tags'           , 'com:tags.model.tags');
        $manager->registerAlias('com:articles.model.categories'     , 'com:categories.model.categories');
        $manager->registerAlias('com:articles.controller.attachment', 'com:attachments.controller.attachment');
        $manager->registerAlias('com:articles.view.attachment.file' , 'com:attachments.view.attachment.file');
    }
}