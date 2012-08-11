<?php

class ComLanguagesViewDashboardHtml extends ComDefaultViewHtml
{
	public function display($tpl = null)
	{
        $nooku  		= $this->getService('com://admin/languages.model.config');
        $dashboard  	= $this->getService('com://admin/languages.model.dashboard');
        $translations	= $this->getService('com://admin/languages.model.translations');
        $translators	= $this->getService('com://admin/languages.model.translators');
       
        // left panes
        /*$this->assignRef('additions',       $dashboard->getAdditions());
        $this->assignRef('changes',         $dashboard->getChanges());
        $this->assignRef('deletes',         $dashboard->getDeletes());
 
		$this->assignRef('all_translators', $nooku->getTranslators());
		$this->assignRef('all_languages', 	$nooku->getLanguages());
		$this->assignRef('all_tables', 		$nooku->getTables());

        // Pie chart URI's
        $this->assign('translations',  		$translations->getGoogleChartUrl());
        $this->assign('translators',   		$translators->getGoogleChartUrl());

        // Meta
        $this->assign('multiple_langs',   	count($nooku->getLanguages()) > 1);
		$this->assign('multiple_contributors', $translators->countContributors() > 1);
		$this->assign('has_tables',   		count($nooku->getTables()) > 0);*/
        
		// Display the layout
		parent::display($tpl);
	}
}
