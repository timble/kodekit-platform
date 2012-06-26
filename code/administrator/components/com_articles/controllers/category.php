<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Category Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesControllerCategory extends ComCategoriesControllerCategory
{ 
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
            'model' => 'categories'
    	));
    
    	parent::_initialize($config);
    }
    
    public function getRequest()
	{
		$this->_request['section'] = 'com_articles';
	    return $this->_request;
	}
}