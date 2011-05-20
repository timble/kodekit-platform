<?php
/**
 * @version      $Id$
 * @category     Nooku
 * @package      Nooku_Server
 * @subpackage   Banners
 * @copyright    Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Banners Controller Class - Banner
 *
 * @author      Cristiano Cucco <cristiano.cucco at gmail dot com>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
class ComBannersControllerClick extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        // register count click after read
        $this->registerCallback('after.read', array($this, 'count_click'));
        
        // get component parameters
        $params = KFactory::tmp('admin::com.banners.helper.params')
                    ->getComponentParams('com_banners');
        if ($params->get('track_clicks')) {
            // register track click after read
            $this->registerCallback('after.read', array($this, 'track_click'));
        }
    }
    
    /**
     * Read action (override to increment hits)
     * 
     * @param KCommandContext $context
     */
    protected function _actionDisplay(KCommandContext $context) 
    { 
        // read row
        $row = $this->execute('read', $context); 
        
        // set redirect
        $url = KFactory::tmp('lib.koowa.http.uri')->set($row->clickurl);
        $this->setRedirect($url);
        // send 307...does not work till dispatcher redirect is refactored
        //$context->status = KHttpResponse::TEMPORARY_REDIRECT;
        
        return $row;
    }
    
    
    /**
     * Increase banner click counter 
     * 
     * @param $context
     */
    public function count_click(KCommandContext $context)
    {
        $context->caller->getModel()->getItem()->click();
    }
    
    /**
     * Track click in bannertrack table
     *  
     * @param $context
     */
    public function track_click(KCommandContext $context)
    {
        $row = $context->caller->getModel()->getItem();
        $row->track($row::CLICK);
    }
    
}