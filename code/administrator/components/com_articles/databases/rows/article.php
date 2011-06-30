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
    public function __get($column)
    {
        if($column == 'params' && !($this->_data['params']) instanceof JParameter)
        {
	        $file = JPATH_BASE.'/components/com_articles/databases/rows/article.xml';

			$params	= new JParameter($this->_data['params']);
			$params->loadSetupFile($file);

			$this->_data['params'] = $params;
        }

        if($column == 'text' && !isset($this->_data['text'])) {
            $this->_data['text'] = $this->fulltext ? $this->introtext.'<hr id="system-readmore" />'.$this->fulltext : $this->introtext;
        }

        return parent::__get($column);
    }

    public function save()
    {
        //Set the section_id based on the category_id
        if(isset($this->_modified['category_id']))
        {
            if($this->category_id != 0)
            {
                $this->_data['section_id'] = KFactory::tmp('admin::com.categories.model.categories')
                    ->set('id', $this->category_id)
                    ->getItem()->section_id;

            }
            else $this->_data['section_id'] = 0;
        }

        //Set the introtext and the full text
        $text    = str_replace('<br>', '<br />', $this->text);
        $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';

        if(preg_match($pattern, $text))
        {
            list($introtext, $fulltext) = preg_split($pattern, $text, 2);

            $this->introtext = trim($introtext);
            $this->fulltext = trim($fulltext);
        }
        else $this->fulltext = trim($text);

        //Validate the title
        if(empty($this->title))
        {
            $this->_status          = KDatabase::STATUS_FAILED;
            $this->_status_message  = JText::_('Article must have a title');

            return false;
        }

        //Validate the text
        if(empty($this->introtext) && empty($this->fulltext))
        {
            $this->_status          = KDatabase::STATUS_FAILED;
            $this->_status_message  = JText::_('Article must have some text');

            return false;
        }

        $modified = $this->_modified;
        $result   = parent::save();

        //Set the featured
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

    public function toArray()
    {
        $data = parent::toArray();

        $data['params'] = $this->params->toArray();
        return $data;
    }
}