<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Banners View
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 */
 
class ModBannersHtml extends ModDefaultHtml
{
    var $_keywords; 
    
    public function display()
    { 
        $this->assign('header_text'    , trim($this->module->params->get('header_text')));
        $this->assign('footer_text'    , trim($this->module->params->get('footer_text')));
        $this->assign('moduleclass_sfx', $this->module->params->get('moduleclass_sfx'));
        
        // Module parameters
        $model = KFactory::tmp('site::com.banners.model.banners')
                    ->enabled(1)
                    ->category($this->module->params->get('catid'))
                    ->sort($this->module->params->get('ordering', 0))
                    ->limit($this->module->params->get('count', 1));
           
        // Manage tags search
        if ($this->module->params->get('tag_search')) {
            $model->tags($this->_getKeywords());
        }
               
	    // Assign vars and render view
		$this->assign('banners', $model->getList());    
         
        return parent::display();
    }
    
    protected function _getKeywords()
    {
        if (!isset($this->_keywords))
        {
            $params = KFactory::get('lib.joomla.application')->getParams();
            $prefix = $params->get( 'tag_prefix' );
            
            // get keywords from document
            $keywords = JFactory::getDocument()->getMetaData( 'keywords' );
            $keywords = explode(',', $keywords);
            
            $page_keywords = array();
            
            foreach ($keywords as $keyword)
            {
                $keyword = trim( $keyword );
                $regex = '#^' . $prefix . '#';
                if (preg_match( $regex, $keyword )) {
                    $page_keywords[] = $keyword;
                }
            }
        }
        
        return $page_keywords;
    }
} 