<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-articles for the canonical source repository
 */

namespace Kodekit\Component\Articles;

use Kodekit\Library;

/**
 * Article Database Row
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Articles
 */
class ModelEntityArticle extends Library\ModelEntityRow
{
    public function save()
    {
        //Validate the title
        if(empty($this->title))
        {
            $this->_status          = self::STATUS_FAILED;
            $this->_status_message  = $this->getObject('translator')->translate('Article must have a title');

            return false;
        }

        return parent::save();
    }

    public function getPropertyText()
    {
        $text = $this->fulltext ? $this->introtext.'<hr id="system-readmore" />'.$this->fulltext : $this->introtext;
        return $text;
    }

    public function setProperty($name, $value, $modified = true)
    {
        //Set the introtext and the fulltext
        if($name == 'text')
        {
            $text    = str_replace('<br>', '<br />', $value);
            $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';

            if(preg_match($pattern, $text))
            {
                list($introtext, $fulltext) = preg_split($pattern, $text, 2);

                $this->introtext = trim($introtext);
                $this->fulltext  = trim($fulltext);
            }
            else
            {
                $this->introtext = trim($text);
                $this->fulltext  = '';
            }
        }

        return parent::setProperty($name, $value, $modified);
    }
}