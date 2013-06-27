<?php
/**
 * @package     Nooku_Server
 * @subpackage  Tags
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Tag Controller Class
 *
 * @author    	Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ArticlesControllerTag extends TagsControllerTag
{ 
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model'   => 'com:tags.model.tags',
            'request' => array(
                'view' => 'tag'
            )
        ));

        parent::_initialize($config);
    }
}