<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Dispatcher Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

class FilesDispatcher extends Library\DispatcherComponent
{
    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $request = parent::getRequest();

        if ($request->getQuery()->get('view', 'cmd') === 'file') {
            $request->setFormat('html');
        }

        return $request;
    }
}