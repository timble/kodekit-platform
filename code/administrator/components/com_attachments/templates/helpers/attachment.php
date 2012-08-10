<?php
class ComAttachmentsTemplateHelperAttachment extends KTemplateHelperAbstract
{
	/**
	 * Builds the file upload control and initializes it's related javascript classes.
	 * 
	 * To enable maximum compliance with the current state of the file upload's accept attribute, 
	 * specify both any MIME types and any corresponding extension. For more information, see 
	 * from http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#attr-input-accept
	 * 
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function upload($config = array())
	{
		$config = new KConfig($config);		
		$config->append(array(
				'holder'	 		 => 'document.body'
		));

		if(!$config->allowed_extensions || !$config->allowed_mimetypes)
		{
			$container = $this->getService('com://admin/files.database.table.containers')
							->select(array('slug' => 'attachments-attachments'), KDatabase::FETCH_ROW);
			
			$config->append(array(
					'allowed_extensions'  => $container->parameters->allowed_extensions,
					'allowed_mimetypes'   => $container->parameters->allowed_mimetypes
			));
		}
		
		if($config->holder != 'document.body') {
			$config->holder = '\''.$config->holder.'\'';
		}
		
		$html = <<<END
		<script src="media://com_attachments/js/attachments.js" />
		<script>
		window.addEvent('domready', function() {
			new Attachments.Upload({
				holder: {$config->holder},
			    extensions: {$config->allowed_extensions->toJson()}
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