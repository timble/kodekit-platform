<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-attachments for the canonical source repository
 */

namespace Kodekit\Component\Attachments;

use Kodekit\Library;

/**
 * Attachment Template Helper
 *
 * @author  Steven Rombauts <http://github.com/stevenrombauts>
 * @package Kodekit\Component\Attachments
 */
class TemplateHelperAttachment extends Library\TemplateHelperAbstract
{
	/**
	 * Builds the file upload control and initializes it's related javascript classes.
	 *
	 * To enable maximum compliance with the current state of the file upload's accept attribute, specify both any MIME
     * types and any corresponding extension.
	 * @see http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#attr-input-accept
	 *
	 * @param mixed $config An optional Library\ObjectConfig object with configuration options
	 */
	public function upload($config = array())
	{
		$config = new Library\ObjectConfig($config);
		$config->append(array(
	        'container'	=> 'document.body'
		));

		if(!$config->allowed_extensions || !$config->allowed_mimetypes)
		{
			$container = $this->getObject('com:files.model.containers')
                ->slug('attachments-attachments')
                ->fetch();

			$config->append(array(
					'allowed_extensions'  => $container->getParameters()->allowed_extensions,
					'allowed_mimetypes'   => $container->getParameters()->allowed_mimetypes
			));
		}

		if($config->container != 'document.body') {
			$config->container = '\''.$config->container.'\'';
		}

        $extensions = json_encode($config->allowed_extensions->toArray());

		$html = <<<END
		<ktml:script src="assets://attachments/js/attachments.upload.js" />
		<script>
		window.addEvent('domready', function() {
			new Attachments.Upload({
				container: {$config->container},
			    extensions: {$extensions}
			});
		});
		</script>
END;

		$accept = array();
		foreach($config->allowed_extensions->toArray() as $val) {
			$accept[] = '.'.$val;
		}

		$accept = array_merge($accept, $config->allowed_mimetypes->toArray());

		$html .= '<input type="file" name="attachments[]" accept="'.implode(', ', $accept).'" />';

		return $html;
	}
}