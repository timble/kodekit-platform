<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Logs
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Message Template Helper Class
 *
 * @author      Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Logs
 */

class ComLogsTemplateHelperMessage extends KTemplateHelperDefault
{
	public function build($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'row'      => ''
		));

		$row = $config->row;
		$url = $this->getTemplate()->getView()->createRoute('index.php?option='.$row->type.'_'.$row->package.'&view='.$row->name.'&id='.$row->row_id);

		$message  = '<a class="ellipsis" href="'.$url.'">'.$row->title.'</a> ';
		$message .= '<br /><small>'.date("H:i", strtotime($row->created_on)).' - '.$row->created_by_name.'</small>';

		return $message;
	}
}