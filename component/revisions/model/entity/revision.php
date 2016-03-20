<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-revisions for the canonical source repository
 */

namespace Kodekit\Component\Revisions;

use Kodekit\Library;

/**
 * Revision Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Revisions
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