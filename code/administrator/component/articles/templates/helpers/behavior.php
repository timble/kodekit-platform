<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Behavior Template Helper
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesTemplateHelperBehavior extends ComDefaultTemplateHelperBehavior
{
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
            'selector'	=> 'table tbody.sortable'
        ))->append(array(
            'options'	=> array(
                'handle'	=> 'td.handle',
                'numcolumn'	=> '.grid-count',
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

        $html = '';//$this->getService('ninja:template.helper.document')->render(array('/sortables.js', '/sortables.css'));

        $signature = md5(serialize(array($config->selector,$config->options)));
        if (!isset($this->_loaded[$signature]))
        {
            $options = !empty($config->options) ? $config->options->toArray() : array();
            $html .= "
                <script src=\"/administrator/templates/default/js/sortables.js\" />
                <style src=\"/administrator/templates/default/css/sortables.css\" />
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