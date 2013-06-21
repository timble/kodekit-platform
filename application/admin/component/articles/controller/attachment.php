<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Attachment Controller Class
 *
 * @author    	Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ArticlesControllerAttachment extends AttachmentsControllerAttachment
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getModel()->getTable()->attachBehavior('com:articles.database.behavior.assignable');
    }
}