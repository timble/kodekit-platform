<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Files;

/**
 * Files Html View
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Component\Files
 */
class FilesViewFilesHtml extends Files\ViewFilesHtml
{
    public function render()
    {
        $base = clone $this->getObject('request')->getBaseUrl();

        $this->sitebase = (string) $base;

        $base->setQuery(array('option' => 'com_files'));
        $this->getObject('application')->getRouter()->build($base);

        $this->base = (string) $base;

        return parent::render();
    }
}