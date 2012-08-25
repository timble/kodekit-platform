<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Article Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesDatabaseRowArticle extends KDatabaseRowDefault
{
    /**
     * Get the author
     *
     * Returns the author alias if any. Otherwise the created_by col is translated to user's name.
     *
     * @return null|string Null is row is new, author alias/name otherwise.
     */
    public function getAuthor()
    {
        $result = null;

        if (!$this->isNew())
        {
            if (!$this->created_by_alias)
            {
                $user = JFactory::getUser($this->created_by);
                $result = $user->name;
            }
            else $this->created_by_alias;
        }

        return $result;
    }

    public function __get($column)
    {
        if($column == 'text' && !isset($this->_data['text'])) {
            $this->_data['text'] = $this->fulltext ? $this->introtext.'<hr id="system-readmore" />'.$this->fulltext : $this->introtext;
        }

        return parent::__get($column);
    }

    public function save()
    {
        //Set the introtext and the full text
        $text    = str_replace('<br>', '<br />', $this->text);
        $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';

        if(preg_match($pattern, $text))
        {
            list($introtext, $fulltext) = preg_split($pattern, $text, 2);

            $this->introtext = trim($introtext);
            $this->fulltext = trim($fulltext);
        } else {
        	$this->introtext = trim($text);
        	$this->fulltext = '';
        }

        //Validate the title
        if(empty($this->title))
        {
            $this->_status          = KDatabase::STATUS_FAILED;
            $this->_status_message  = JText::_('Article must have a title');

            return false;
        }

        $modified = $this->_modified;
        $result   = parent::save();

        //Set the featured
        if(isset($modified['featured']))
        {    
  			$featured = $this->getService('com://admin/articles.database.row.featured')
  			     ->set('id', $this->id);
            
            if($this->featured)
            {
                if(!$featured->load()) {           
                    $featured->save();
                }
            }
            else
            {
                if($featured->load()) {
                    $featured->delete();
                }
            }
        }

        return $result;
    }

    public function delete()
    {
        $result = parent::delete();

        $featured = $this->getService('com://admin/articles.database.row.featured')
             ->set('id', $this->id);

        if($featured->load()) {
            $featured->delete();
        }

        return $result;
    }

    public function toArray()
    {
        $data = parent::toArray();

        $data['params'] = $this->params->toArray();
        return $data;
    }
}