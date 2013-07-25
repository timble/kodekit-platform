<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Component\Ckeditor;

use Nooku\Library;
use Nooku\Component\Files;

/**
 * Files Html View Class
 *
 * @author      Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Ckeditor
 */

class ViewFilesHtml extends Files\ViewFilesHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'auto_assign' => false
        ));

        parent::_initialize($config);
    }

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
