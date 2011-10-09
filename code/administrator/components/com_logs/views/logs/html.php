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
 * Logs View Html
 *
 * @author      Israel Canasa <israel@timble.net>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Logs
 */

class ComLogsViewLogsHtml extends ComDefaultViewHtml
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		$this->getTemplate()
			->getFilter('alias')
			->append(array(
				'@date(' => '$this->getView()->friendlyDate(',
				'@timeago(' => '$this->getView()->timeAgo('
			));
	}
	
	public function display()
	{
		// Fetch the items only when layout is default
		if ($this->getLayout() == 'default') 
		{
			// Get through getService so we're getting a new instance of the model.
			$model = $this->getService($this->getModel()->getIdentifier());

			$this->assign('packages', $model
				->distinct(true)
				->column('package')
				->getList()
			);

			$this->assign('actions', $model
				->distinct(true)
				->column('action')
				->getList()
			);
		} return parent::display();
	}

	public function friendlyDate($date, $format = 'M d, Y h:i A')
	{
		return date($format, strtotime($date));
	}

	public function timeAgo($date)
	{
		$d1 = (is_string($date) ? strtotime($date) : $date);
		$d2 = time();

		$diff_secs = abs($d1 - $d2);
		$base_year = min(date("Y", $d1), date("Y", $d2));

		$base_month = date("n", $d2);

		$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);

		$result = array(
			'years' => date('Y', $diff) - $base_year,
			'months_total' => (date('Y', $diff) - $base_year) * 12 + date('n', $diff),
			'months' => $base_month - date('n', $d1),
			'days_total' => floor($diff_secs / (3600 * 24)),
			'days' => date('j', $diff) - 1,
			'hours_total' => floor($diff_secs / 3600),
			'hours' => date('G', $diff),
			'minutes_total' => floor($diff_secs / 60),
			'minutes' => (int) date('i', $diff),
			'seconds_total' => $diff_secs,
			'seconds' => (int) date('s', $diff)
		);

		if ($result['years'] >= 1) {
			return $result['years'].' '.JText::_(($result['years'] > 1) ? 'Years' : 'Year') .' Ago';
		}elseif($result['months_total'] <= 12 && $result['months_total'] >= 3 && $result['years'] == 0){
			return JText::_('This Year');
		}elseif($result['months_total'] >= 0 && $result['months'] > 0 ){
			return JText::_('Previous Months');
		}elseif($result['months'] == 0 && $result['days'] > 1){
			return JText::_('This Month');
		}elseif($result['days_total'] < 7 && $result['days_total'] > 0){
			return JText::_('Past 7 Days');
		}elseif($result['days_total'] <= 0){
			return JText::_('Today');
		}
	}
}