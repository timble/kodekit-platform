<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Article Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesDatabaseRowArticle extends KDatabaseRowDefault
{
    public function save()
    {
        if($this->created_on && strlen(trim($this->created_on)) <= 10) {
            $this->created_on .= ' 00:00:00';
        }

        if(strlen(trim($this->publish_up)) <= 10) {
            $this->publish_up .= ' 00:00:00';
        }

        if(trim($this->publish_down) == JText::_('Never') || trim($this->publish_down) == '') {
            $this->publish_down = $this->getTable()->getDefault('publish_down');
        } elseif(strlen(trim($this->publish_down)) <= 10) {
            $this->publish_down .= ' 00:00:00';
        }

        if(is_array($this->params))
        {
            foreach($params as $key => $value) {
                $attribs[] = $key.'='.$value;
            }

            $this->attribs = implode(PHP_EOL, $attribs);
        }

        $text    = str_replace('<br>', '<br />', $this->text);
        $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';

        if(preg_match($pattern, $text))
        {
            list($this->introtext, $this->fulltext) = preg_split($pattern, $text, 2);

            $this->introtext = trim($this->introtext);
            $this->fulltext  = trim($this->fulltext);
        }
        else
        {
            $this->introtext = trim($text);
        }

        if(empty($this->title))
        {
            $this->_status          = KDatabase::STATUS_FAILED;
            $this->_status_message  = JText::_('Article must have a title');

            return false;
        }

        if(empty($this->introtext) && empty($this->fulltext))
        {
            $this->_status          = KDatabase::STATUS_FAILED;
            $this->_status_message  = JText::_('Article must have some text');

            return false;
        }

        if(!empty($this->meta_keywords))
        {
            $keys = explode(',', str_ireplace(array("\n", "\r", '"', '<', '>'), '', $this->meta_keywords));
            $keys = array_filter(array_map('trim', $keys));

            $this->meta_keywords = implode(', ', $keys);
        }

        if(!empty($this->meta_description)) {
            $this->meta_description = str_ireplace(array('"', '<', '>'), '', $this->meta_description);
        }

        $this->meta_data = implode(PHP_EOL, array('robots='.$this->meta_robots, 'author='.$this->meta_author));

        $modified = $this->_modified;
        $result   = parent::save();

        if(isset($modified['featured']))
        {
            $featured     = KFactory::tmp('admin::com.articles.database.row.featured');
            $featured->id = $this->id;

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

        // TODO: Add cache cleaning.

        return $result;
    }

    public function delete()
    {
        $result = parent::delete();

        $featured     = KFactory::tmp('admin::com.articles.database.row.featured');
        $featured->id = $this->id;

        if($featured->load()) {
            $featured->delete();
        }

        return $result;
    }

    public function __get($name)
    {
        switch($name)
        {
            case 'text':
                if(!isset($this->text)) {
                    $this->text = $this->fulltext ? $this->introtext.'<hr id="system-readmore" />'.$this->fulltext : $this->introtext;
                }
                break;

            case 'meta_robots':
            case 'meta_author':
                if(!isset($this->meta_robots) || !isset($this->meta_author))
                {
                    if($this->meta_data)
                    {
                        list($robots, $author) = explode(PHP_EOL, $this->meta_data);

                        $robots = trim(substr($robots, strpos($robots, '=') + 1));
                        $author = trim(substr($author, strpos($author, '=') + 1));
                    }

                    $this->meta_robots = isset($robots) ? $robots : '';
                    $this->meta_author = isset($author) ? $author : '';
                }
                break;
        }

        return parent::__get($name);
    }
}