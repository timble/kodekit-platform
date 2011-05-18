<?php
/**
 * @version		$Id: select.php 1309 2011-05-17 16:47:27Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * String Template Helper Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchTemplateHelperString extends KTemplateHelperAbstract
{	
    /**
	 * Returns a summary of a text, highlights and generated a substring by default
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return string
	 */
    public function summary($config)
	{
	    $config = new KConfig($config);
	    $config->append(array(
	        'text'		 => '',
			'term'		 => '',
			'match' 	 => 'exact',
	        'lenght'     => 200,
	        'highlight'  => true,
	        'substring'  => true
		));
	    
		if($config->match != 'exact') 
	    {
			$words = preg_split('/\s+/u', $config->term);
			$needle = $words[0];
		} 
		else  $needle = $config->term;
				
		// Strips tags won't remove the actual jscript
		$config->text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $config->text );
		$config->text = preg_replace( '/{.+?}/', '', $config->text);
		
		// Replace line breaking tags with whitespace
		$config->text = preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $config->text );

		//Return a substring of characters around the term
		if($config->substring) {
		    $config->text = $this->substring($config);
		}
		
		if($config->highlight) {
		    $config->text = $this->highlight($config);
		}
			
		return $config->text;
	}
    
	/**
	 * Returns a highlighted string based on a term
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return string
	 */
    public function highlight($config)
	{
	    $config = new KConfig($config);
	    $config->append(array(
	        'text'		=> '',
			'term'		=> '',
			'match' 	=> 'exact',
		));
	    
	    if($config->match == 'exact') {
			$words = array($config->term);
		} else {
			$words = preg_split('/\s+/u', $config->term);
		}
			
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
		$text = preg_replace($hlregex, '<span class="highlight">\0</span>', $config->text);
		
		return $text;
	}
	
	/**
	 * Returns substring of characters around a term
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return string 
	 */
	public function substring($config)
	{
		$config = new KConfig($config);
	    $config->append(array(
	        'text'		 => '',
			'term'		 => '',
	        'lenght'     => 200,
		));
	    
	    $textlen     = JString::strlen($config->text);
		$lsearchword = JString::strtolower($config->term);
		$found       = false;
		$pos         = 0;
		
		$config->text = strip_tags( $config->text );
		
		while ($found === false && $pos < $textlen) 
		{
			if (($wordpos = @JString::strpos($config->text, ' ', $pos + $config->lenght)) !== false) {
				$chunk_size = $wordpos - $pos;
			} else {
				$chunk_size = $config->lenght;
			}
			
			$chunk = JString::substr($config->text, $pos, $chunk_size);
			$found = JString::strpos(JString::strtolower($chunk), $lsearchword);
			
			if ($found === false) {
				$pos += $chunk_size + 1;
			}
		}

		if ($found == false) 
		{
			if (($wordpos = @JString::strpos($config->text, ' ', $config->lenght)) !== false) {
				return JString::substr($config->text, 0, $wordpos) . '&nbsp;...';
			} else {
				return JString::substr($config->text, 0, $config->lenght);
			}
		}
		else return (($pos > 0) ? '...&nbsp;' : '') . $chunk . '&nbsp;...';
	}
}
