<?php
/**
 * @version     $Id: articles.php 1633 2011-06-07 19:24:17Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Archive Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesViewArchiveHtml extends ComDefaultViewHtml
{
    public function display()
    {
		// Request variables
		$task 		= JRequest::getCmd('task');
		$limit		= $mainframe->getUserStateFromRequest('com_content.'.$this->getLayout().'.limit', 'limit', $params->get('display_num', 20), 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$month		= JRequest::getInt( 'month' );
		$year		= JRequest::getInt( 'year' );
		$filter		= JRequest::getString( 'filter' );
		JRequest::setVar('limit', (int) $limit);

		// Pathway
		$pathway = KFactory::get('lib.joomla.application')->getPathway();
		$pathway->addItem(JText::_('Archive'), '');

		//Params
		$params  =  KFactory::get('lib.joomla.application')->getParams('com_content');
		$params->def('filter',		1);
		$params->def('filter_type',	'title');

		//Menu
		$menu = JSite::getMenu()->getActive();

		if (is_object( $menu )) 
		{
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'Archives' ));
			}
		} 
		else $params->set('page_title',	JText::_( 'Archives' ));
	
		//Document
		KFactory::get('lib.joomla.document')->setTitle( $params->get( 'page_title' ) );

		//Form fields
		$form = new stdClass();
		
		$months = array(
			JHTML::_('select.option',  null, JText::_( 'Month' ) ),
			JHTML::_('select.option',  '01', JText::_( 'JANUARY_SHORT' ) ),
			JHTML::_('select.option',  '02', JText::_( 'FEBRUARY_SHORT' ) ),
			JHTML::_('select.option',  '03', JText::_( 'MARCH_SHORT' ) ),
			JHTML::_('select.option',  '04', JText::_( 'APRIL_SHORT' ) ),
			JHTML::_('select.option',  '05', JText::_( 'MAY_SHORT' ) ),
			JHTML::_('select.option',  '06', JText::_( 'JUNE_SHORT' ) ),
			JHTML::_('select.option',  '07', JText::_( 'JULY_SHORT' ) ),
			JHTML::_('select.option',  '08', JText::_( 'AUGUST_SHORT' ) ),
			JHTML::_('select.option',  '09', JText::_( 'SEPTEMBER_SHORT' ) ),
			JHTML::_('select.option',  '10', JText::_( 'OCTOBER_SHORT' ) ),
			JHTML::_('select.option',  '11', JText::_( 'NOVEMBER_SHORT' ) ),
			JHTML::_('select.option',  '12', JText::_( 'DECEMBER_SHORT' ) )
		);
		
		$form->monthField	= JHTML::_('select.genericlist',   $months, 'month', 'size="1" class="inputbox"', 'value', 'text', $month );

		$years = array();
		$years[] = JHTML::_('select.option',  null, JText::_( 'Year' ) );
		for ($i=2000; $i <= 2010; $i++) {
			$years[] = JHTML::_('select.option',  $i, $i );
		}
		
		$form->yearField	= JHTML::_('select.genericlist',   $years, 'year', 'size="1" class="inputbox"', 'value', 'text', $year );

		$this->assign('filter' 	, $filter);
		$this->assign('year'  	, $year);
		$this->assign('month' 	, $month);

		$this->assign('form'    ,	$form);
		$this->assign('params'  ,   $params);
		
		parent::display();
	}
}