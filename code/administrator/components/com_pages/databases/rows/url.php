<?php
/**
 * @version     $Id: url.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Url Database Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseRowUrl extends ComPagesDatabaseRowPage
{
    protected function _getPageXml()
    {
        if(!isset($this->_page_xml))
        {
            $xml  = JFactory::getXMLParser('simple');
            $type = $this->getType();
            $path = JPATH_APPLICATION.'/components/com_pages/databases/rows/url.xml';

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
            $title = JText::_('URL');
            $this->_data['type_title'] = $title;
        }

        if($column == 'type_description' && !isset($this->_data['type_description'])) {
            $this->_data['type_description'] = JText::_('External Link');
        }

        if($column == 'params_path' && !isset($this->_data['params_path']))
        {
            $path = JPATH_BASE.'/components/com_pages/databases/rows/url.xml';
            $this->_data['params_path'] = $path;
        }

        return parent::__get($column);
   }
}
