<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Component\Tags
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function tags($config = array())
    {
        $config = new Library\ObjectConfig($config);
    	$config->append(array(
            'package' => $this->getTemplate()->getIdentifier()->package,
    		'value'	  => 'title',
    		'label'	  => 'title',
            'prompt'   => false,
            'deselect' => false,
        ))->append(array(
            'model'  => $this->getObject('com:tags.model.tags', array('table' => $config->package.'_tags')),
        ));

        $config->label = 'title';
		$config->sort  = 'title';

    	return parent::_render($config);
    }
}