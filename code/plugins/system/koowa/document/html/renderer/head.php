<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Document
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Head renderer
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_Document
 * @subpackage	Html
 */
class KDocumentHtmlRendererHead extends KDocumentRenderer
{
	/**
	 * Renders the document head and returns the results as a string
	 *
	 * @param string 	$name		(unused)
	 * @param array 	$params		Associative array of values
	 * @return string	The output of the script
	 */
	public function render( $head, array $params = array(), $content = null )
	{
		ob_start();

		echo $this->renderHead($this->_doc);

		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	/**
	 * Generates the head html and return the results as a string
	 *
	 * @return string
	 */
	public function renderHead($document)
	{
		$strHtml = '';
		
		// Get the head data from the document
		$data = $document->getHeadData();

		// Generate base tag (need to happen first)
		$base = $document->getBase();
		if(!empty($base)) {
			$strHtml .= '	<base href="'.$document->getBase().'" />'.PHP_EOL;
		}

		// Generate META tags (needs to happen as early as possible in the head)
		foreach ($data['metaTags'] as $type => $tag)
		{
			foreach ($tag as $name => $content)
			{
				if ($type == 'http-equiv') {
					$strHtml .= '	<meta http-equiv="'.$name.'" content="'.$content.'" />'.PHP_EOL;
				} elseif ($type == 'standard') {
					$strHtml .= '	<meta name="'.$name.'" content="'.$content.'" />'.PHP_EOL;
				}
			}
		}

		$strHtml .= '	<meta name="description" content="'.$document->getDescription().'" />'.PHP_EOL;
		$strHtml .= '	<meta name="generator" content="'.$document->getGenerator().'" />'.PHP_EOL;

		$strHtml .= '	<title>'.htmlspecialchars($document->getTitle()).'</title>'.PHP_EOL;

		// Generate link declarations
		foreach ($document->_links as $link) {
			$strHtml .= '	'.$link.' />'.PHP_EOL;
		}

		// Generate stylesheet links
		foreach ($data['styleSheets'] as $strSrc => $strAttr )
		{
			$strHtml .= '	<link rel="stylesheet" href="'.$strSrc.'" type="'.$strAttr['mime'].'"';
			if (!is_null($strAttr['media'])){
				$strHtml .= ' media="'.$strAttr['media'].'" ';
			}
			if ($temp = KHelperArray::toString($strAttr['attribs'])) {
				$strHtml .= ' '.$temp;;
			}
			$strHtml .= ' />'.PHP_EOL;
		}

		// Generate stylesheet declarations
		foreach ($data['style'] as $type => $content)
		{
			$strHtml .= '	<style type="'.$type.'">';

			// This is for full XHTML support.
			if ($document->getMimeEncoding() == 'text/html' ) {
				$strHtml .= '		<!--';
			} else {
				$strHtml .= '		<![CDATA[';
			}

			$strHtml .= $content;

			// See above note
			if ($document->getMimeEncoding() == 'text/html' ) {
				$strHtml .= '		-->';
			} else {
				$strHtml .= '		]]>';
			}
			$strHtml .= '	</style>'.PHP_EOL;
		}

		// Generate script file links
		foreach ($data['scripts'] as $strSrc => $strType) {
			$strHtml .= '	<script type="'.$strType.'" src="'.$strSrc.'"></script>'.PHP_EOL;
		}

		// Generate script declarations
		foreach ($data['script'] as $type => $content)
		{
			$strHtml .= '	<script type="'.$type.'">';

			// This is for full XHTML support.
			if ($document->getMimeEncoding() != 'text/html' ) {
				$strHtml .= '		<![CDATA[';
			}

			$strHtml .= $content;

			// See above note
			if ($document->getMimeEncoding() != 'text/html' ) {
				$strHtml .= '		// ]]>';
			}
			$strHtml .= '	</script>'.PHP_EOL;
		}

		foreach($data['custom'] as $custom) {
			$strHtml .= $custom.PHP_EOL;
		}

		return $strHtml;
	}
}