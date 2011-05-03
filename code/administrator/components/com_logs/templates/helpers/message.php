<?php
/** $Id: message.php 1852 2010-12-13 19:40:51Z tomjanssens $ */

class ComLogsTemplateHelperMessage extends ComDefaultTemplateHelperString
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