<?php
KLoader::load('site::com.articles.mappings');

echo KFactory::get('site::com.articles.dispatcher')->dispatch();