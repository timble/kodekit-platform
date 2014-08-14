<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Revisions;

use Nooku\Library;

/**
 * Revision Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Revisions
 */
class ModelEntityRevision extends Library\ModelEntityRow
{
    public function setStatus($status)
    {
        if($status == 'trashed') {
            parent::setStatus(self::STATUS_DELETED);
        }

        $this->_status = $status;
        return $this;
    }
}