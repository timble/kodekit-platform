<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php
class articleIcons
{
	function create($article, $params, $access, $attribs = array())
	{
		$uri = clone JFactory::getURI();
		$ret = $uri->toString();	
		$url = 'index.php?task=new&ret='.base64_encode($ret).'&id=0&sectionid='.$article->sectionid;
		if ($params->get('show_icons')) {
			$text = JHTML::_('image.site', 'new.png', '/images/M_images/', NULL, NULL, JText::_('New') );
		} else {
			$text = JText::_('New').'&nbsp;';
		}
		$attribs	= array( 'title' => JText::_( 'New' ));
		return JHTML::_('link', JRoute::_($url), $text, $attribs);
	}
	
	function email($article, $params, $access, $attribs = array())
	{
		$uri	= clone JURI::getInstance();
		$base	= $uri->toString( array('scheme', 'host', 'port'));
		$link	= $base.JRoute::_( ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid) , false );
		$url	= 'index.php?option=com_mailto&tmpl=component&link='.base64_encode( $link );
		$status = 'width=400,height=350,menubar=yes,resizable=yes';
		if ($params->get('show_icons')){
			$text = '<span class="email-icon">'.JText::_('Email').'</span>';
		} else {
			$text = JText::_('Email');
		}
		$attribs['title']	= JText::_( 'Email' );
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
		$output = JHTML::_('link', JRoute::_($url), $text, $attribs);
		return $output;
	}
	
	function edit($article, $params, $access, $attribs = array())
	{
		$user =& JFactory::getUser();
		$uri = clone JFactory::getURI();
		$ret = $uri->toString();
		if ($params->get('popup')) {
			return;
		}
		if ($article->state < 0) {
			return;
		}
		if (!$access->canEdit && !($access->canEditOwn && $article->created_by == $user->get('id'))) {
			return;
		}
		$url = 'index.php?view=article&id='.$article->slug.'&task=edit&ret='.base64_encode($ret);
		$icon = $article->state ? 'edit.png' : 'edit_unpublished.png';
		$text = JHTML::_('image.site', $icon, '/images/M_images/', NULL, NULL, JText::_('Edit'));
		if ($article->state == 0) {
			$overlib = JText::_('Unpublished');
		} else {
			$overlib = JText::_('Published');
		}
		$date = JHTML::_('date', $article->created);
		$author = $article->created_by_alias ? $article->created_by_alias : $article->author;
		$overlib .= '&lt;br /&gt;';
		$overlib .= JText::_($article->groups);
		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= htmlspecialchars($author, ENT_COMPAT, 'UTF-8');
		$button = JHTML::_('link', JRoute::_($url), $text);
		$output = '<span class="tooltip edit icon" title="'.$overlib.'">'.$button.'</span>';
		return $output;
	}
	
	function print_popup($article, $params, $access, $attribs = array())
	{
		$url  = 'index.php?view=article';
		$url .=  @$article->catslug ? '&catid='.$article->catslug : '';
		$url .= '&id='.$article->slug.'&tmpl=component&print=1&layout=default&page='.@ $request->limitstart;
		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
		if ( $params->get( 'show_icons' ) ) {
			$text = '<span class="print-icon">'.JText::_('Print').'</span>';
		} else {
			$text = JText::_('Print');
		}
		$attribs['title']	= JText::_( 'Print' );
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
		$attribs['rel']     = 'nofollow';

		return JHTML::_('link', JRoute::_($url), $text, $attribs);
	}
	
	function print_screen($article, $params, $access, $attribs = array()) 
	{
		if ( $params->get( 'show_icons' ) ) {
			$text = '<span class="print-icon">'.JText::_('Print').'</span>';
		} else {
			$text = JText::_('Print');
		}
		return '<a href="#" onclick="window.print();return false;">'.$text.'</a>';
	}
}