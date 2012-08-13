<?php
/**
 * @version     $Id: link.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Pagelink Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseRowPagelink extends ComPagesDatabaseRowPage
{
    protected function _getPageXml()
    {
        if(!isset($this->_page_xml))
        {
            $xml  = JFactory::getXMLParser('simple');
            $type = $this->getType();
            $path = JPATH_APPLICATION.'/components/com_pages/databases/rows/pagelink.xml';

            if(file_exists($path)) {
                $xml->loadFile($path);
            }

            $this->_page_xml = $xml;
        }

        return $this->_page_xml;
    }

    public function __get($column)
    {
        if($column == 'type_title' && !isset($this->_data['type_title']))
        {
            $title = JText::_('Page Link');
            $this->_data['type_title'] = $title;
        }

        if($column == 'type_description' && !isset($this->_data['type_description'])) {
            $this->_data['type_description'] = JText::_('Page Link');
        }

        return parent::__get($column);
   }
}
