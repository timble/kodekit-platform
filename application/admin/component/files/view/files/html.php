<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Files;

/**
 * Files Html View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
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