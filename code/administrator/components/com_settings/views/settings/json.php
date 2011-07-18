<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Settings Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 */

class ComSettingsViewSettingsJson extends KViewJson
{
    public function display()
    {
        $model = $this->getModel();

        if(KInflector::isPlural($this->getName())) {
            $data = array('settings' => $model->getList()->toArray());
        } else {
            $data = $model->getItem()->toArray();
        }

        $this->output = $data;

        return parent::display();
    }
}