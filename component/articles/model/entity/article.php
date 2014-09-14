<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Articles;

use Nooku\Library;

/**
 * Article Database Row
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Articles
 */
class ModelEntityArticle extends Library\ModelEntityRow
{
    public function save()
    {
        //Set the introtext and the full text
        $text    = str_replace('<br>', '<br />', $this->text);
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
        if($name == 'modified_on' && empty($value)) {
            $value = $this->created_on;
        }

        if($name == 'modified_by' && empty($value)) {
            $value = $this->created_by;
        }

        return parent::setProperty($name, $value, $modified);
    }
}