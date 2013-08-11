<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Comments;

use Nooku\Library;

/**
 * Image Template Helper
 *
 * @author  Steven Rombauts <https://nooku.assembla.com/profile/stevenrombauts>
 * @package Nooku\Component\Comments
 */
class TemplateHelperImage extends Library\TemplateHelperAbstract
{   
    public function gravatar($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'email'  => '',
            'size'  => '32',
            'attribs' => array()
        ));
        
        $source = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $config->email ) ) ) . "?s=".$config->size;

        $html = '<img class="avatar" src="'.$source.'" />';

        return $html;
    }
}
