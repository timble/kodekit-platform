<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Newsfeeds Helper Class - Filter
 *
 * @author      Babs Gšsgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */

class ComNewsfeedsTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function category($config = array())
	{
	    $config = new Kconfig($config);

        $list = KFactory::tmp('admin::com.categories.model.categories')
            ->set('section', 'com_newsfeeds')
            ->set('limit', 0)
            ->getList();

        $options   = array();
        $options[] = $this->option(array('text' => '- '.JText::_( 'Select').' -'));

 		foreach($list as $item) {
			$options[] =  $this->option(array('text' => $item->title, 'value' => $item->id));
		}

        $config->options = $options;

        return parent::optionlist($config);
	}
}
