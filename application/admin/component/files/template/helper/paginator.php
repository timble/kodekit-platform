<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Paginator Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Files
 */
class FilesTemplateHelperPaginator extends ApplicationTemplateHelperPaginator
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
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'limit'   => 0,
        ));

        $translator = $this->getObject('translator');

        $html = '';
        $html .= '<div class="pagination">';
        $html .= '<div class="limit">'.$translator('Display NUM').' '.$this->limit($config).'</div>';
        $html .=  $this->pages($config);
        $html .= '<div class="limit"> '.$translator('Page').' <span class="page-current">1</span>';
        $html .= ' '.$translator('of').' <span class="page-total">1</span></div>';
        $html .= '</div>';

        return $html;
    }

    public function pages($config = array())
    {
        $config = new Library\ModelPaginator($config);
		$config->append(array(
			'total'      => 0,
			'display'    => 4,
			'offset'     => 0,
			'limit'	     => 0,
			'attribs'	=> array(),
		));

		$html   = '<div class="button__group">'.$this->link($config->pages->first).'</div>';
		$html  .= '<div class="button__group">';
		$html  .= $this->link($config->pages->prev);
		$html  .= '</div>';
		$html  .= '<div class="button__group page-list"></div>';
		$html  .= '<div class="button__group">';
		$html  .= $this->link($config->pages->next);
		$html  .= '</div>';
		$html  .= '<div class="button__group">'.$this->link($config->pages->last).'</div>';

		return $html;
    }
}