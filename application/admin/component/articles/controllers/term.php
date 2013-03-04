<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Category Controller Class
 *
 * @author    	Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesControllerTerm extends ComTermsControllerTerm
{ 
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'model'   => 'com://admin/terms.model.terms',
            'request' => array(
                'view' => 'term'
            )
        ));

        parent::_initialize($config);
    }
}