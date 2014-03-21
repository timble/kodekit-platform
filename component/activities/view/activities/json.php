<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activities JSON View Class
 *
 * @author  Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Activities
 * @see 	http://activitystrea.ms/specs/json/1.0/
 */
class ViewActivitiesJson extends Library\ViewJson
{
    protected function _getItem(Library\DatabaseRowInterface $row)
    {
        $data = parent::_getItem($row);

        unset($data['links']); // Cleanup.

        return $data;
    }

    protected function _getActivity(Library\DatabaseRowInterface $row)
    {
        $id = array(
            'tag:'.$this->getUrl()->toString(Library\HttpUrl::BASE),
            'id:'.$row->id
        );

        $template = $this->getObject('template.default', array('view' => $this));
        $item = array(
            'id' => implode(',', $id),
            'published' => $this->getObject('com:activities.template.helper.date', array('template' => $template))->format(array(
                    'date'   => $row->created_on,
                    'format' => 'Y-m-dTZ'
                )),
            'verb' => $row->action,
            'object' => array(
                'url' => (string)$this->getRoute('option=com_'.$row->package.'&view='.$row->name.'&id='.$row->row),
            ),
            'target' => array(
                'url' => (string)$this->getRoute('option=com_'.$row->package.'&view='.$row->name),
            ),
            'actor' => array(
                'url' => (string)$this->getRoute('option=com_users&view=user&id='.$row->created_by),
            )
        );

        return $item;
    }
}
