<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Attachments;

use Nooku\Library;

/**
 * Attachable Database Behavior
 *
 * @author  Steven Rombauts <https://nooku.assembla.com/profile/stevenrombauts>
 * @package Nooku\Component\Attachments
 */
class DatabaseBehaviorAttachable extends Library\DatabaseBehaviorAbstract
{
    /**
     * Get a list of attachments
     *
     * @return RowsetAttachments
     */
    public function getAttachments()
	{
        $model = $this->getObject('com:attachments.model.attachments');

        if(!$this->isNew())
        {
            $attachments = $model->row($this->id)
                ->table($this->getTable()->getBase())
                ->getRowset();
        }
        else $attachments = $model->getRowset();

        return $attachments;
	}
}