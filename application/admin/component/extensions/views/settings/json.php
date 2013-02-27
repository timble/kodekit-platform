<?php
/**
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Settings Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Extensions
 */

class ComExtensionsViewSettingsJson extends KViewJson
{
    public function display()
    {
        $model = $this->getModel();

        if(KInflector::isPlural($this->getName())) {
            $data = array('settings' => $model->getRowset()->toArray());
        } else {
            $data = $model->getRow()->toArray();
        }

        $this->setContent($data);
        return parent::display();
    }
}