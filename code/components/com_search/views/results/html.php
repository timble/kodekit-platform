<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Search Html View Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchViewResultsHtml extends ComDefaultViewHtml
{
	
	/**
	 * Initializes the config for the object
	 *
	 * Called from {@link __construct()} as a first step of object instantiation.
	 *
	 * @param     object     An optional KConfig object with configuration options
	 * @return  void 
	 */
	protected function _initialize(KConfig $config)
	{
		// Force model, default layout and manual data assignation.
		$config->append(array( 
			'auto_assign'    => false
		));
		
		parent::_initialize($config);
	}
	
	/**
	 * Return the views output
	 * 
	 * This function will auto assign the model data to the view if the auto_assign
	 * property is set to TRUE.
	 *
	 * @return string     The output of the view
	 */
	public function display()
	{
		$params = KFactory::get('lib.joomla.application')->getParams();
		
		$model = $this->getModel();
		$state = $model->getState();
		
		$results = $model->getList();
		
		foreach($results as $result) 
		{	
			if($state->match == 'exact') 
			{
				$words = array($state->term);
				$needle = $state->term;
			} 
			else 
			{
				$words = preg_split('/\s+/u', $state->term);
				$needle = $words[0];
			}
			
			// Output filtering
			$result->text = $this->prepareSearchContent($result->text, 200, $needle);
			
			// Highlight search words
			$words = array_unique($words);
			$hlregex = '#(';
			$x = 0;
			
			foreach($words as $k => $hlword) 
			{
				$hlregex .= ($x == 0 ? '' : '|');
				$hlregex .= preg_quote($hlword, '#');
				$x++;
			}
			
			$hlregex .= ')#iu';
			$result->text = preg_replace($hlregex, '<span class="highlight">\0</span>', $result->text);
			
			// Pretty timezone aware date (if any)
			if($result->created) {
				$result->created = JHTML::Date($result->created);
			} else {
				$result->created = '';
			}
		}
		
		$this->assign('offset'         , $state->offset)
			 ->assign('results'        , $results)
			 ->assign('params'         , $params)
			 ->assign('term'           , $state->term)
			 ->assign('total'          , $model->getTotal())
			 ->assign('search_areas'   , $model->getAreas());
		
		return parent::display();
	}

	/**
	 * Prepares results from search for display
	 *
	 * @param string The source string
	 * @param int Number of chars to trim
	 * @param string The searchword to select around
	 * @return string
	 */
	public function prepareSearchContent( $text, $length = 200, $searchword )
	{
		// Strips tags won't remove the actual jscript
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
		$text = preg_replace( '/{.+?}/', '', $text);
		
		// Replace line breaking tags with whitespace
		$text = preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text );

		return $this->_smartSubstr( strip_tags( $text ), $length, $searchword );
	}
	
	/**
	 * Returns substring of characters around a searchword
	 *
	 * @param string The source string
	 * @param int Number of chars to return
	 * @param string The searchword to select around
	 * @return string
	 */
	function _smartSubstr($text, $length = 200, $searchword)
	{
		$textlen     = JString::strlen($text);
		$lsearchword = JString::strtolower($searchword);
		$found       = false;
		$pos         = 0;
		
		while ($found === false && $pos < $textlen) 
		{
			if (($wordpos = @JString::strpos($text, ' ', $pos + $length)) !== false) {
				$chunk_size = $wordpos - $pos;
			} else {
				$chunk_size = $length;
			}
			
			$chunk = JString::substr($text, $pos, $chunk_size);
			$found = JString::strpos(JString::strtolower($chunk), $lsearchword);
			
			if ($found === false) {
				$pos += $chunk_size + 1;
			}
		}

		if ($found == false) 
		{
			if (($wordpos = @JString::strpos($text, ' ', $length)) !== false) {
				return JString::substr($text, 0, $wordpos) . '&nbsp;...';
			} else {
				return JString::substr($text, 0, $length);
			}
		}
		else return (($pos > 0) ? '...&nbsp;' : '') . $chunk . '&nbsp;...';
	}
}