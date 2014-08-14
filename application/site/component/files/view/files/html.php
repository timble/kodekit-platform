<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Files;

/**
 * Files Html View
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Component\Files
 */
class FilesViewFilesHtml extends Files\ViewFilesHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $base = clone $this->getObject('request')->getBaseUrl();

        $context->data->sitebase = (string) $base;

        // FIXME: take out the hardcoded Itemid
        $base->setQuery(array('component' => 'files', 'Itemid' => 56));
        $this->getObject('application')->getRouter()->build($base);

        $context->data->base = (string) $base;

        parent::_fetchData($context);
    }
}