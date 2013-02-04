<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Search
 * @copyright      Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Articles Search Module Html Class
 *
 * @author        Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package       Nooku_Server
 * @subpackage    Search
 */
class ComArticlesModuleSearchHtml extends ComDefaultModuleDefaultHtml
{
    public function display()
    {
        $this->form_class   = $this->module->params->get('form_class', 'form-search');
        $this->input_class  = $this->module->params->get('input_class', 'span2 search-query');
        $this->button_class = $this->module->params->get('button_class', 'btn');
        $this->placeholder  = $this->module->params->get('placeholder', 'Search articles');
        $this->item_id      = $this->module->params->get('item_id', null);

        return parent::display();
    }
}