<?php

class ComLanguagesToolbarButtonSetdefault extends KToolbarButtonPost
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_query = $config->query->toArray();
	}

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
			'text' => JText::_('Default'),
        	'icon' => 'icon-32-default',
			'query' => array(
				'option' => 'com_languages',
				'view' => 'language',
				'client' => ''
			)
        ));

        parent::_initialize($config);
    }
	
    public function getOnClick()
    {
		$query = http_build_query($this->_query);

        $token  = JUtility::getToken();
        $json   = "{method:'post', url:'index.php?$query&'+id, params:{action:'edit', default:1, _token:'$token'}}";

        $msg    = JText::_('Please select an item from the list');
        return 'var id = Koowa.Grid.getIdQuery();'
            .'if(id){new Koowa.Form('.$json.').submit();} '
            .'else { alert(\''.$msg.'\'); return false; }';
    }
}
