<?php
KFactory::get('admin::com.harbour.dispatcher')
	->dispatch(KRequest::get('get.view', 'cmd', 'boats'));
