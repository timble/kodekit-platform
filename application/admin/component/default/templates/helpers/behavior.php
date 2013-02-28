<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Behavior Helper
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultTemplateHelperBehavior extends KTemplateHelperBehavior
{
    /**
     * Keep session alive
     *
     * This will send an ascynchronous request to the server via AJAX on an interval
     *
     * @return string   The html output
     */
    public function keepalive($config = array())
    {
        $session = $this->getService('user')->getSession();
        if($session->isActive())
        {
            //Get the config session lifetime
            $lifetime = $session->getLifetime() * 1000;

            //Refresh time is 1 minute less than the liftime
            $refresh =  ($lifetime <= 60000) ? 30000 : $lifetime - 60000;

            $config = new KConfig($config);
            $config->append(array(
                'refresh' => $refresh
            ));

            return parent::keepalive($config);
        }
    }

    /**
     * Drag and Drop Sortables Behavior
     *
     * Examples:
     * <code>
     * // Outside a template layout
     * $helper = $this->getService('ninja:template.helper.behavior');
     * $helper->sortable();
     * <tbody class="sortable"><tr class="sortable"><td class="handle"></td></tr></tbody>
     *
     * // Inside a template layout
     * <?= @ninja('behavior.sortable') ?>
     * <tbody class="sortable"><tr class="sortable"><td class="handle"></td></tr></tbody>
     * </code>
     *
     * @param 	array 	An optional array with configuration options
     * @return	string 	Html
     */
    public function sortable($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'option'	=> KRequest::get('get.option', 'cmd'),
            'view'		=> KInflector::singularize(KRequest::get('get.view', 'cmd')),
            'selector'	=> 'table tbody.sortable',
            'direction' => 'asc'
        ))->append(array(
            'options'	=> array(
                'handle'	=> 'td.handle',
                'numcolumn'	=> '.grid-count',
                'direction' => $config->direction,
                'adapter'	=> array(
                    'type'		=> 'koowa',
                    'options'	=> array(
                        'url'		=> '?format=json',
                        'data'	=> array(
                            '_token'	=> $this->getService('user')->getSession()->getToken(),
                            '_action'	=> 'edit'
                        ),
                        'key'		=> 'order',
                        'offset'	=> 'relative'
                    )
                )
            )
        ));

        $html = '';

        $signature = md5(serialize(array($config->selector,$config->options)));
        if (!isset($this->_loaded[$signature]))
        {
            $options = !empty($config->options) ? $config->options->toArray() : array();
            $html .= "
                <script src=\"/administrator/theme/bootstrap/js/sortables.js\" />
                <style src=\"/administrator/theme/bootstrap/css/sortables.css\" />
				<script>
				(function(){
					var sortable = function() {
						$$('".$config->selector."').sortable(".json_encode($options).");
					};
					window.addEvents({domready: sortable, request: sortable});
				})();
				</script>
			";

            $this->_loaded[$signature] = true;
        }

        return $html;
    }
}