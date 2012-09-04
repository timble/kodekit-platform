<?php
/**
 * @version     $Id: module.php 4997 2012-08-29 19:39:25Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */

class ComLanguagesControllerComponent extends ComDefaultControllerResource
{
    protected function _actionEdit(KCommandContext $context)
    {
        $request = $this->getRequest();
        if($id = KConfig::unbox($request->id))
        {
            $this->getService('com://admin/languages.model.tables')
                ->component($id)
                ->getList()
                ->setData(array('enabled' => (int) $context->data->enabled))
                ->save();
        }
    }
}