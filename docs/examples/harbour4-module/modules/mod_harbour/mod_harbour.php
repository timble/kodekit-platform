<?php
KFactory::get('site::mod.harbour.html', array(
	'params'  => $params,
	'module'  => $module,
	'attribs' => $attribs
))->display();