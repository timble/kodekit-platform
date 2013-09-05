<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Title Template Filter
 *
 * Filter to parse <title></title> tags. Filter will loop over all the title tags. By default only first found none
 * empty tag will be used, other tags are ignored.
 *
 * Subsequent tags should define the content="[append\prepend\replace]" attribute to append to, prepend to or replace
 * the initial tag. The separator, default '-' can either be passed though the filters configuration options or can be
 * defined as an extra attribute.  Eg, <title content="prepend" separator="|">[title]</title>
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateFilterTitle extends TemplateFilterTag
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'separator' => '-',
            'escape'    => true,
        ));

        parent::_initialize($config);
    }

    /**
	 * Parse the text for script tags
	 *
	 * @param string $text  The text to parse
	 * @return string
	 */
	protected function _parseTags(&$text)
	{
		$tags  = '';
        $title =  '';

		$matches = array();
        if(preg_match_all('#<title(.*)>(.*)<\/title>#siU', $text, $matches))
		{
            foreach(array_unique($matches[2]) as $key => $match)
            {
                //Set required attributes
                $attribs = array(
                    'content'   => 'default',
                    'separator' => $this->getConfig()->separator
                );

                $attribs   = array_merge($attribs, $this->parseAttributes( $matches[1][$key]));
                $separator = $attribs['separator'];

                if(!empty($title))
                {
                    switch($attribs['content'])
                    {
                        case 'prepend' : $title = $match.' '.$separator.' '.$title; break;
                        case 'append'  : $title = $title.' '.$separator.' '.$match; break;
                        case 'replace' : $title = $match; break;
                    }
                }
                else $title = $match;
            }

            $text = str_replace($matches[0], '', $text);
            $tags .= $this->_renderTag($attribs, $title);
        }

		return $tags;
	}

    /**
     * Render the tag
     *
     * @param 	array	$attribs Associative array of attributes
     * @param 	string	$content The tag content
     * @return string
     */
    protected function _renderTag($attribs = array(), $content = null)
	{
        unset($attribs['content']);
        unset($attribs['separator']);

        $attribs = $this->buildAttributes($attribs);

        if($this->getConfig()->escape) {
            $content = $this->getTemplate()->escape($content);
        }

		$html = '<title '.$attribs.'>'.$content.'</title>'."\n";
		return $html;
	}
}