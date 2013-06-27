<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Articles Bootstrapper
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Server
 * @subpackage  Articles
 */
 class ArticlesBootstrapper extends Library\BootstrapperAbstract
{
    public function bootstrap()
    {
        $manager = $this->getObjectManager();

        $manager->registerAlias('com:articles.model.tags'           , 'com:tags.model.tags');
        $manager->registerAlias('com:articles.model.categories'     , 'com:categories.model.categories');
        $manager->registerAlias('com:articles.view.attachment.file' , 'com:attachments.view.attachment.file');
    }
}