<?php
class ComFilesTemplateHelperPaginator extends ComDefaultTemplateHelperPaginator
{
    /**
     * Render item pagination
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     * @see     http://developer.yahoo.com/ypatterns/navigation/pagination/
     */
    public function pagination($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'limit'   => 0,
        ));

        $html  = '<div class="container">';
        $html .= '<div class="pagination" id="files-paginator">';
        $html .= '<div class="limit">'.JText::_('Display NUM').' '.$this->limit($config->toArray()).'</div>';
        $html .=  $this->_pages();
        $html .= '<div class="limit"> '.JText::_('Page').' <span class="page-current">1</span>';
        $html .= ' '.JText::_('of').' <span class="page-total">1</span></div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render a list of pages links
     *
     * This function is overriddes the default behavior to render the links in the khepri template
     * backend style.
     *
     * @param   araay   An array of page data
     * @return  string  Html
     */
    protected function _pages()
    {
    	$tpl = '<div class="button2-%s"><div class="%s"><a href="#">%s</a></div></div>';

    	$html = sprintf($tpl, 'right', 'start', JText::_('First'));
    	$html .= sprintf($tpl, 'right', 'prev', JText::_('Prev'));
    	$html .= '<div class="button2-left"><div class="page"></div></div>';
    	$html .= sprintf($tpl, 'left', 'next', JText::_('Next'));
    	$html .= sprintf($tpl, 'left', 'end', JText::_('Last'));

        return $html;
    }

	public function limit($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'limit'	  	=> 0,
			'attribs'	=> array(),
		));

		$html = '';

		$selected = '';
		foreach(array(10, 20, 50, 100) as $value)
		{
			if($value == $config->limit) {
				$selected = $value;
			}

			$options[] = $this->option(array('text' => $value, 'value' => $value));
		}

		$html .= $this->optionlist(array('options' => $options, 'name' => 'limit', 'attribs' => $config->attribs, 'selected' => $selected));
		return $html;
	}
}