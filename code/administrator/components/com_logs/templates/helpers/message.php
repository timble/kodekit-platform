<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Logs
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

class ComLogsTemplateHelperMessage extends KTemplateHelperDefault
{
	public function build($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'row'      => ''
		));

		//Get the row object
		$row = $config->row;

		$message  = '<a class="ellipsis" href="index.php?option='.$row->type.'_'.$row->package.'&view='.$row->name.'&id='.$row->row_id.'">'.$row->title.'</a> ';
		$message .= '<br /><small>'.date("H:i", strtotime($row->created_on)).' - '.$row->created_by_name.'</small>';

		return $message;
	}
}