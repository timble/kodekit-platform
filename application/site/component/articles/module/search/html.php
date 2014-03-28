<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Search Module Html View
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Articles
 */
class ArticlesModuleSearchHtml extends PagesModuleDefaultHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $params = $this->module->getParameters();

        $context->data->form_class   = $params->get('form_class', 'form-search');
        $context->data->input_class  = $params->get('input_class', 'span2 search-query');
        $context->data->button_class = $params->get('button_class', 'btn');
        $context->data->placeholder  = $params->get('placeholder', 'Search articles');
        $context->data->item_id      = $params->get('item_id', null);

        parent::_fetchData($context);
    }
}