<?php

/*
 * Create test files
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->name('random_test_file.txt')
	->post(array('contents' => 'test', 'overwrite' => 1))
	->toArray();
$result = KObject::get('com:files.controller.folder')
	->container('files-files')
	->name('random_test_folder')
	->post()
	->toArray();

/*
 * Root file - move
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->name('random_test_file.txt')
	->move(array('destination_name' => 'moved_test_file.txt'))
	->toArray();

var_dump('Root file - move', $result,
	!file_exists(JPATH_ROOT.'/images/random_test_file.txt') && file_exists(JPATH_ROOT.'/images/moved_test_file.txt'));

/*
 * Root file - copy
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->name('moved_test_file.txt')
	->copy(array('destination_name' => 'copied_test_file.txt'))
	->toArray();

var_dump('Root file - copy', $result,
	file_exists(JPATH_ROOT.'/images/moved_test_file.txt') && file_exists(JPATH_ROOT.'/images/copied_test_file.txt'));

/*
 * Nested file - move
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->name('moved_test_file.txt')
	->move(array('destination_folder' => 'random_test_folder'))
	->toArray();

var_dump('Nested file - move', $result,
	!file_exists(JPATH_ROOT.'/images/moved_test_file.txt') && file_exists(JPATH_ROOT.'/images/random_test_folder/moved_test_file.txt'));

/*
 * Nested file - copy
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->name('copied_test_file.txt')
	->copy(array('destination_folder' => 'random_test_folder'))
	->toArray();

var_dump('Nested file - copy', $result,
	file_exists(JPATH_ROOT.'/images/copied_test_file.txt') && file_exists(JPATH_ROOT.'/images/random_test_folder/copied_test_file.txt'));

/*
 * Nested file - copy with a new name
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->name('copied_test_file.txt')
	->copy(array('destination_folder' => 'random_test_folder', 'destination_name' => 'copied_test_file2.txt'))
	->toArray();

var_dump('Nested file - copy with a new name', $result,
	file_exists(JPATH_ROOT.'/images/copied_test_file.txt') && file_exists(JPATH_ROOT.'/images/random_test_folder/copied_test_file2.txt'));

/*
 * Nested file - move with a new name
 */
$result = KObject::get('com:files.controller.file')
	->container('files-files')
	->name('copied_test_file.txt')
	->move(array('destination_folder' => 'random_test_folder', 'destination_name' => 'moved_test_file2.txt'))
	->toArray();

var_dump('Nested file - move with a new name', $result,
	!file_exists(JPATH_ROOT.'/images/copied_test_file.txt') && file_exists(JPATH_ROOT.'/images/random_test_folder/moved_test_file2.txt'));

/*
 * Delete test files
 */
$result = KObject::get('com:files.controller.folder')
	->container('files-files')
	->name('random_test_folder')
	->delete()
	->toArray();