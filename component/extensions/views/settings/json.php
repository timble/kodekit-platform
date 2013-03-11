<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Extensions;

use Nooku\Framework;

/**
 * Settings Json View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class ViewSettingsJson extends Framework\ViewJson
{
    public function render()
    {
        $model = $this->getModel();

        if(Framework\Inflector::isPlural($this->getName())) {
            $data = array('settings' => $model->getRowset()->toArray());
        } else {
            $data = $model->getRow()->toArray();
        }

        $this->setContent($data);

        return parent::render();
    }
}