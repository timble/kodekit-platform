<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Block Template Filter
 *
 * Filter will parse elements of the form <khtml:block name="[name]" condition=[condition]"> as named blocks
 * and elements of the form <khtml:block (extend|prepend|replace)="[name]">[content]</html:block> to be
 * injected into the named block.
 *
 * By default blocks will be appended, they can also be prepended [prepend] or replaced [replace] the named
 * block.
 *
 * The block will not be rendered if there are no block extending it, an optional condition attribute can be
 * defined to define a more advanced condition as to when the placeholder should be rendered. Only if the
 * condition evaluates to TRUE the block will be rendered.
 *
 * Example <khtml:block name="sidebar" condition="sidebar > 2"> In this case the sidebar will be rendered only
 * if at least two blocks have been injected.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
class TemplateFilterBlock extends TemplateFilterDecorator
{
    /**
     * Names list of blocks
     *
     * @var	array
     */
    private $__blocks;

    /**
     * Parse tags
     *
     * @param string $text Block of text to parse
     * @return void
     */
    public function filter(&$text)
    {
        parent::filter($text);

        $this->_parseTags($text);
    }

    /**
     * Add a block
     *
     * @param string $name The name of the block
     * @param array  $data The data of the block
     * @return TemplateFilterBlock
     */
    public function addBlock($name, array $data)
    {
        if(!isset($this->__blocks[$name])) {
            $this->__blocks[$name] = array();
        }

        $this->__blocks[$name][] = $data;

        return $this;
    }

    /**
     * Get the blocks by name
     *
     * @param $name
     * @return array List of blocks
     */
    public function getBlocks($name)
    {
        $result = array();

        if(isset($this->__blocks[$name])) {
            $result = $this->__blocks[$name];
        }

        return $result;
    }

    /**
     * Check if blocks exists
     *
     * @param $name
     * @return bool TRUE if blocks exist, FALSE otherwise
     */
    public function hasBlocks($name)
    {
        return isset($this->__blocks[$name]) && !empty($this->__blocks[$name]);
    }

    /**
     * Clear the blocks by name
     *
     * @param string $name The name of the block
     * @return TemplateFilterBlock
     */
    public function clearBlocks($name)
    {
        if($this->hasBlocks($name)) {
            unset($this->__blocks[$name]);
        }

        return $this;
    }

    /**
     * Parse tags
     *
     * @param string $text Block of text to parse
     */
    protected function _parseTags(&$text)
    {
        $replace = array();
        $matches = array();

        // <ktml:block extend|prepend|replace="[name]"></khtml:block>
        if(preg_match_all('#<ktml:block\s+(extend|prepend|replace)="([^"]+)"(.*)>(.*)</ktml:block>#siU', $text, $matches))
        {
            $count = count($matches[0]);

            for($i = 0; $i < $count; $i++)
            {
                $name = $matches[2][$i];

                //Create attributes array
                $defaults = array(
                    'title'   => '',
                    'extend'  => ''
                );

                $attributes = array_merge($defaults, $this->parseAttributes($matches[3][$i]));

                //Create block
                $block = array(
                    'content'  => $matches[4][$i],
                    'title'    => $attributes['title'],
                    'extend'   => $matches[1][$i],
                    'attribs'  => (array) array_diff_key($attributes, $defaults)
                );

                //Clear any prior added blocks
                if($block['extend'] == 'replace') {
                    $this->clearBlocks($name);
                }

                $this->addBlock($name, $block);

                //Do not continue adding blocks
                if($block['extend'] == 'replace') {
                    break;
                }
            }

            //Remove the tags
            $text = str_replace($matches[0], '', $text);
        }

        // <ktml:block name="[name]" condition="[condition]"></khtml:block>
        if(preg_match_all('#<ktml:block\s+name="([^"]+)"(.*"[^"]*")?>(.*)</ktml:block>#siU', $text, $matches))
        {
            $count = count($matches[1]);

            for($i = 0; $i < $count; $i++)
            {
                $name = $matches[1][$i];

                if($this->hasBlocks($name))
                {
                    $attribs     = $this->parseAttributes( $matches[2][$i] );
                    $replace[$i] = '';

                    if(isset($attribs['condition']))
                    {
                        if($this->_countBlocks($attribs['condition']))
                        {
                            unset($attribs['condition']);
                            $replace[$i] = $this->_renderBlocks($name, $attribs);
                        }
                    }
                    else $replace[$i] = $this->_renderBlocks($name, $attribs);

                    if(!empty($replace[$i])) {
                        $replace[$i] = str_replace('<ktml:block:content>', $replace[$i], $matches[3][$i]);
                    }
                }
            }

            $text = str_replace($matches[0], $replace, $text);
        }

        $replace = array();
        $matches = array();
        // <ktml:blocks name="[name]" condition="[condition]">
        if(preg_match_all('#<ktml:block\s+name="([^"]+)"(.*"[^"]*")?>#iU', $text, $matches))
        {
            $count = count($matches[1]);

            for($i = 0; $i < $count; $i++)
            {
                $name = $matches[1][$i];

                if($this->hasBlocks($name))
                {
                    $attribs     = $this->parseAttributes( $matches[2][$i]);
                    $replace[$i] = '';

                    if(isset($attribs['condition']))
                    {
                        if($this->_countBlocks($attribs['condition']))
                        {
                            unset($attribs['condition']);
                            $replace[$i] = $this->_renderBlocks($name, $attribs);
                        }
                    }
                    else $replace[$i] = $this->_renderBlocks($name, $attribs);

                }
            }

            $text = str_replace($matches[0], $replace, $text);
        }
    }

    /**
     * Render the blocks
     *
     * @param array  $name     The name of the block
     * @param array  $attribs  List of block attributes
     * @return string   The rendered block
     */
    protected function _renderBlocks($name, $attribs = array())
    {
        $html   = '';
        $count  = 1;
        $blocks = $this->getBlocks($name);

        foreach($blocks as $block)
        {
            //Set the block attributes
            if($count == 1) {
                $attribs['rel']['first'] = 'first';
            }

            if($count == count($blocks)) {
                $attribs['rel']['last'] = 'last';
            }

            if(isset($block['attribs'])) {
                $block['attribs'] = array_merge((array) $block['attribs'], $attribs);
            } else {
                $block['attribs'] = $attribs;
            }

            //Render the block
            $content = $this->_renderBlock($block);

            //Prepend or append the block
            if($block['extend'] == 'prepend') {
                $html = $content.$html;
            } else {
                $html = $html.$content;
            }

            $count++;
        }

        return $html;
    }

    /**
     * Render a block
     *
     * @param array     $block   The block data
     * @return string   The rendered block
     */
    protected function _renderBlock($block)
    {
        $result = '';

        if(isset($block['content'])) {
            $result = $block['content'];
        }

        return $result;
    }

    /**
     * Count the modules based on a condition
     *
     * @param  string $condition
     * @return integer Returns the result of the evaluated condition
     */
    protected function _countBlocks($condition)
    {
        $operators = '(\+|\-|\*|\/|==|\!=|\<\>|\<|\>|\<=|\>=|and|or|xor)';
        $words = preg_split('# ' . $operators . ' #', $condition, null, PREG_SPLIT_DELIM_CAPTURE);
        for ($i = 0, $n = count($words); $i < $n; $i += 2)
        {
            // Odd parts (blocks)
            $name = strtolower($words[$i]);

            if(!is_numeric($name)) {
                $words[$i] = count($this->getBlocks($name));
            } else {
                $words[$i] = $name;
            }
        }

        //Use the stream buffer to evaluate the condition
        $str = '<?php return ' . implode(' ', $words) .';';

        $buffer = $this->getObject('filesystem.stream.factory')->createStream('nooku-buffer://temp', 'w+b');
        $buffer->truncate(0);
        $buffer->write($str);

        $result = include $buffer->getPath();

        return $result;
    }
}