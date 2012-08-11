<?php

class ComLanguagesModelTranslations extends KModelAbstract
{
    public function getStats()
    {
        // Get models
        $nooku = KFactory::get('admin::com.nooku.model.nooku');
        $nodes = KFactory::get('admin::com.nooku.model.nodes');

        // Get data
        $languages  = $nooku->getLanguages();

        // Get State
        $table_name = $this->getState('table_name');
        $iso_code   = $this->getState('iso_code');

        $results    = array();
        foreach ($languages as $lang)
        {
            if($iso_code && $iso_code != $lang->iso_code) {
				continue;
            }

            $conds = array();
            if($table_name) {
                $conds['table_name'] = array('n.table_name', '=', $table_name);
            }
            
            $conds['deleted']	= array('n.deleted', '=', 0);

            $conds['iso_code']  = array('n.iso_code', '=', $lang->iso_code);
            $results['TOTAL'][$lang->iso_code]   = $nodes->count($conds);
			@$results['ALL']['TOTAL'] 			+= $nodes->count($conds);

            $conds['status']    = array('n.status','=', Nooku::STATUS_COMPLETED);
            $results['COMPLETED'][$lang->iso_code]   = $nodes->count($conds);
			@$results['ALL']['COMPLETED'] 			+= $nodes->count($conds);

            $conds['status']    = array('n.status', '=', Nooku::STATUS_MISSING);
            $results['MISSING'][$lang->iso_code]     = $nodes->count($conds);
			@$results['ALL']['MISSING'] 			+= $nodes->count($conds);

            $conds['status']    = array('n.status', '=', Nooku::STATUS_OUTDATED);
            $results['OUTDATED'][$lang->iso_code]    = $nodes->count($conds);
			@$results['ALL']['OUTDATED'] 			+= $nodes->count($conds);

            $conds['status']    = array('n.status', '=', Nooku::STATUS_PENDING);
            $results['PENDING'][$lang->iso_code]     = $nodes->count($conds);
			@$results['ALL']['PENDING'] 			+= $nodes->count($conds);
			
			$conds['status']    = array('n.original', '=', 1);
            $results['ORIGINAL'][$lang->iso_code]    = $nodes->count($conds);
			@$results['ALL']['ORIGINAL'] 			+= $nodes->count($conds);
			
			$conds['deleted']	= array('n.deleted', '=', 0);
			unset($conds['status']);
            $results['DELETED'][$lang->iso_code]     = $nodes->count($conds);
			@$results['ALL']['DELETED'] 			+= $nodes->count($conds);
        }

        return $results;
    }

    public function getGoogleChartUrl()
    {
    	$c = KChartGoogle::getInstance(KChartGoogle::PIE);

        $data = $this->getStats();
        $data = $data['ALL'];
        unset($data['TOTAL']);
        unset($data['ORIGINAL']);
        unset($data['DELETED']);
        // add this back in when we implement 'pending' feature
        unset($data['PENDING']);

		$color = new NookuConfigColor();
        $color->setPrefix('');
		$colors = array();
		
		// Clean out empty parts, so the resutling graph looks cleaner
		if($data['COMPLETED']) {
			$colors[] = $color->get('green');         
		} else {
			unset($data['COMPLETED']);
		}
		
    	if($data['MISSING']) {
			$colors[] = $color->get('red');         
		} else {
			unset($data['MISSING']);
		}
		
    	if($data['OUTDATED']) {
			$colors[] = $color->get('yellow');         
		} else {
			unset($data['OUTDATED']);
		}
		
        // ucfirst labels and send through JText
        $labels = array_keys($data);
        array_walk($labels, array($this, '_cleanLabels'));

		// add Data, labels, and colors
        $c->addData($data)
          ->setValueLabels($labels)
          ->setColors($colors);

          return $c->getUrl();
    }
    
    public function getDefaultState()
    {
       	$app 	= KFactory::get('lib.joomla.application');
    	
    	//Get the namespace
    	$ns  = $this->getClassName('prefix').'.'.$this->getClassName('suffix');

        $state = array(); 
        $state['table_name'] = KInput::get('table_name', array('post', 'get'), KFactory::tmp('admin::com.nooku.filter.tablename'), null, '');
        $state['iso_code']   = KInput::get('iso_code', array('post', 'get'), 'lang', null, '');
        return $state;
    }

    /**
     * Callback function to clean up labels
     *
     * @param	string Label
     */
    protected function _cleanLabels(&$label)
    {
    	$label = JText::_(ucfirst(strtolower($label)));
    }
}