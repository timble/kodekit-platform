<?php
KLoader::loadIdentifier('com://site/comments.aliases');

echo KService::get('com://site/comments.dispatcher')->dispatch();