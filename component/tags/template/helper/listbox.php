<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-tags for the canonical source repository
 */

namespace Kodekit\Component\Tags;

use Kodekit\Library;

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

    	return parent::render($config);
    }
}