<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Shorttag Template Filter
 *
 * Filter for short_open_tags support. Also provides support for auto-escaping.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateFilterShorttag extends TemplateFilterAbstract implements TemplateFilterCompiler
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => TemplateFilter::PRIORITY_HIGH,
        ));

        parent::_initialize($config);
    }

    /**
	 * Convert <?= ?> and <? ?> to long-form <?php echo ?> when needed
	 *
	 * @param string $text  The text to parse
	 * @return void
	 */
	public function compile(&$text)
	{
        if (!ini_get('short_open_tag'))
        {
           /**
         	* We could also convert <%= like the real T_OPEN_TAG_WITH_ECHO but that's not necessary.
         	*
         	* It might be nice to also convert PHP code blocks <? ?> but let's quit while we're ahead.  It's probably
            * better to keep the <?php for larger code blocks but that's your choice.  If you do go for it, explicitly
            * check for <?xml as this will probably be the biggest headache.
         	*/

        	// convert "<?=" to "<?php echo"
       	 	$find = '/\<\?\s*=\s*(.*?)/';
        	$replace = "<?php echo \$1";
        	$text = preg_replace($find, $replace, $text);

        	// convert "<?" to "<?php"
        	$find = '/\<\?(?!xml)(?:php)?\s*(.*?)/';
        	$replace = "<?php \$1";
        	$text = preg_replace($find, $replace, $text);
        }
	}
}