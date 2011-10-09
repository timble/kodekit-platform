<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Behavior Template Helper
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesTemplateHelperBehavior extends ComDefaultTemplateHelperBehavior
{
	public function calendar($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'date'	  => gmdate("M d Y H:i:s"),
		    'name'    => '',
		    'format'  => '%Y-%m-%d %H:%M:%S',
		    'attribs' => array('size' => 25, 'maxlenght' => 19),
		    'gmt_offset' => JFactory::getConfig()->getValue('config.offset') * 3600
 		));
 
        if($config->date && $config->date != '0000-00-00 00:00:00') { 
            $config->date = strftime($config->format, strtotime($config->date) /*+ $config->gmt_offset*/);
        }
        
	    $html = '';
		// Load the necessary files if they haven't yet been loaded
		if (!isset(self::$_loaded['calendar']))
		{
			$html .= '<script src="media://system/js/calendar.js" />';
			$html .= '<script src="media://system/js/calendar-setup.js" />';
			$html .= '<style src="media://system/css/calendar-jos.css" />';
			
			$html .= '<script>'.$this->_calendartranslation().'</script>';

			self::$_loaded['calendar'] = true;
		}
	   
		$html .= "<script>
					window.addEvent('domready', function() {Calendar.setup({
        				inputField     :    '".$config->name."',     	 
        				ifFormat       :    '".$config->format."',   
        				button         :    'button-".$config->name."', 
        				align          :    'Tl',
        				singleClick    :    true,
        				showsTime	   :    false
    				});});
    			</script>";
		
		$attribs = KHelperArray::toString($config->attribs);

   		$html .= '<input type="text" name="'.$config->name.'" id="'.$config->name.'" value="'.$config->date.'" '.$attribs.' />';
		$html .= '<img class="calendar" src="media://system/images/calendar.png" alt="calendar" id="button-'.$config->name.'" />';
		
		return $html;
	}
	
	/**
	 * Method to get the internationalisation script/settings for the JavaScript Calendar behavior.
	 *
	 * @return string	The html output
	 */
	protected function _calendartranslation()
	{
		// Build the day names array.
		$dayNames = array(
			'"'.JText::_('Sunday').'"',
			'"'.JText::_('Monday').'"',
			'"'.JText::_('Tuesday').'"',
			'"'.JText::_('Wednesday').'"',
			'"'.JText::_('Thursday').'"',
			'"'.JText::_('Friday').'"',
			'"'.JText::_('Saturday').'"',
			'"'.JText::_('Sunday').'"'
		);

		// Build the short day names array.
		$shortDayNames = array(
			'"'.JText::_('Sun').'"',
			'"'.JText::_('Mon').'"',
			'"'.JText::_('Tue').'"',
			'"'.JText::_('Wed').'"',
			'"'.JText::_('Thu').'"',
			'"'.JText::_('Fri').'"',
			'"'.JText::_('Sat').'"',
			'"'.JText::_('Sun').'"'
		);

		// Build the month names array.
		$monthNames = array(
			'"'.JText::_('January').'"',
			'"'.JText::_('February').'"',
			'"'.JText::_('March').'"',
			'"'.JText::_('April').'"',
			'"'.JText::_('May').'"',
			'"'.JText::_('June').'"',
			'"'.JText::_('July').'"',
			'"'.JText::_('August').'"',
			'"'.JText::_('September').'"',
			'"'.JText::_('October').'"',
			'"'.JText::_('November').'"',
			'"'.JText::_('December').'"'
		);

		// Build the short month names array.
		$shortMonthNames = array(
			'"'.JText::_('January_short').'"',
			'"'.JText::_('February_short').'"',
			'"'.JText::_('March_short').'"',
			'"'.JText::_('April_short').'"',
			'"'.JText::_('May_short').'"',
			'"'.JText::_('June_short').'"',
			'"'.JText::_('July_short').'"',
			'"'.JText::_('August_short').'"',
			'"'.JText::_('September_short').'"',
			'"'.JText::_('October_short').'"',
			'"'.JText::_('November_short').'"',
			'"'.JText::_('December_short').'"'
		);

		// Build the script.
		$i18n = array(
			'// Calendar i18n Setup.',
			'Calendar._FD = 0;',
			'Calendar._DN = new Array ('.implode(', ', $dayNames).');',
			'Calendar._SDN = new Array ('.implode(', ', $shortDayNames).');',
			'Calendar._MN = new Array ('.implode(', ', $monthNames).');',
			'Calendar._SMN = new Array ('.implode(', ', $shortMonthNames).');',
			'',
			'Calendar._TT = {};',
			'Calendar._TT["INFO"] = "'.JText::_('About the calendar').'";',
			'Calendar._TT["PREV_YEAR"] = "'.JText::_('Prev. year (hold for menu)').'";',
			'Calendar._TT["PREV_MONTH"] = "'.JText::_('Prev. month (hold for menu)').'";',
			'Calendar._TT["GO_TODAY"] = "'.JText::_('Go Today').'";',
			'Calendar._TT["NEXT_MONTH"] = "'.JText::_('Next month (hold for menu)').'";',
			'Calendar._TT["NEXT_YEAR"] = "'.JText::_('Next year (hold for menu)').'";',
			'Calendar._TT["SEL_DATE"] = "'.JText::_('Select date').'";',
			'Calendar._TT["DRAG_TO_MOVE"] = "'.JText::_('Drag to move').'";',
			'Calendar._TT["PART_TODAY"] = "'.JText::_('(Today)').'";',
			'Calendar._TT["DAY_FIRST"] = "'.JText::_('Display %s first').'";',
			'Calendar._TT["WEEKEND"] = "0,6";',
			'Calendar._TT["CLOSE"] = "'.JText::_('Close').'";',
			'Calendar._TT["TODAY"] = "'.JText::_('Today').'";',
			'Calendar._TT["TIME_PART"] = "'.JText::_('(Shift-)Click or drag to change value').'";',
			'Calendar._TT["DEF_DATE_FORMAT"] = "'.JText::_('%Y-%m-%d').'";',
			'Calendar._TT["TT_DATE_FORMAT"] = "'.JText::_('%a, %b %e').'";',
			'Calendar._TT["WK"] = "'.JText::_('wk').'";',
			'Calendar._TT["TIME"] = "'.JText::_('Time:').'";',
			'',
			'"Date selection:\n" +',
			'"- Use the \xab, \xbb buttons to select year\n" +',
			'"- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +',
			'"- Hold mouse button on any of the above buttons for faster selection.";',
			'',
			'Calendar._TT["ABOUT_TIME"] = "\n\n" +',
			'"Time selection:\n" +',
			'"- Click on any of the time parts to increase it\n" +',
			'"- or Shift-click to decrease it\n" +',
			'"- or click and drag for faster selection.";',
			''
		);

		return implode("\n", $i18n);
	}
}