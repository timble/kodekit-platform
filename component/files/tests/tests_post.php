<?php

/*
 * Root file - add
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->name('ercan.txt')
	->add(array('contents' => 'test'))
	->toArray();

var_dump('Root file - add', $result, file_exists(JPATH_ROOT.'/images/ercan.txt'));

/*
 * Root file - edit
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->name('ercan.txt')
	->edit(array('contents' => 'after edit'))
	->toArray();

var_dump('Root file - edit', $result, file_get_contents(JPATH_ROOT.'/images/ercan.txt') === 'after edit');

/*
 * Root file - delete
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->name('ercan.txt')
	->delete()
	->toArray();

var_dump('Root file - delete', $result, !file_exists(JPATH_ROOT.'/images/ercan.txt'));

/*
 * Nested file - add
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->folder('banners')
	->name('nested.txt')
	->add(array('contents' => 'test'))
	->toArray();

var_dump('Nested file - add', $result, file_exists(JPATH_ROOT.'/images/banners/nested.txt'));

/*
 * Nested file - delete
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->folder('banners')
	->name('nested.txt')
	->delete()
	->toArray();

var_dump('Nested file - delete', $result, !file_exists(JPATH_ROOT.'/images/banners/nested.txt'));

/*
 * Root folder - add
 */
$result = KObject::get('com:files.controller.folder')
	->container('files-files')
	->name('ercan_test')
	->add()
	->toArray();

var_dump('Root folder - add', $result, is_dir(JPATH_ROOT.'/images/ercan_test'));

/*
 * Root folder - delete
 */
$result = KObject::get('com:files.controller.folder')
	->container('files-files')
	->name('ercan_test')
	->delete()
	->toArray();

var_dump('Root folder - delete', $result, !file_exists(JPATH_ROOT.'/images/ercan_test'));

/*
 * Nested folder - add
 */
$result = KObject::get('com:files.controller.folder')
	->container('files-files')
	->folder('banners')
	->name('ercan_nested')
	->add()
	->toArray();

var_dump('Nested folder - add', $result, is_dir(JPATH_ROOT.'/images/banners/ercan_nested'));

/*
 * Nested folder - delete
 */
$result = KObject::get('com:files.controller.folder')
	->container('files-files')
	->folder('banners')
	->name('ercan_nested')
	->delete()
	->toArray();

var_dump('Nested folder - delete', $result, !file_exists(JPATH_ROOT.'/images/banners/nested.txt'));
