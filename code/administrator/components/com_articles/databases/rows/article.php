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
        if(isset($this->_modified['category_id']))
        {
            if($this->category_id != 0)
            {
                $this->section_id = KFactory::tmp('admin::com.categories.model.categories')
                    ->set('id', $this->category_id)
                    ->getItem()->section_id;
                    
            } 
            else $this->section_id = 0;
        }

        $text    = str_replace('<br>', '<br />', $this->text);
        $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';

        if(preg_match($pattern, $text))
        {
            list($this->introtext, $this->fulltext) = preg_split($pattern, $text, 2);

            $this->introtext = trim($this->introtext);
            $this->fulltext  = trim($this->fulltext);
        }
        else $this->introtext = trim($text);

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

        if(!empty($this->description)) {
            $this->description = str_ireplace(array('"', '<', '>'), '', $this->description);
        }

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
}