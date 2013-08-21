<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
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


        $this->type = $this->getObject('request')->query->get('type', 'string');

        $this->sitebase = (string) $base;

        $base->setQuery(array('option' => 'com_files'));
        $this->getObject('application')->getRouter()->build($base);

        $this->base = (string) $base;

        return parent::render();
    }
}
