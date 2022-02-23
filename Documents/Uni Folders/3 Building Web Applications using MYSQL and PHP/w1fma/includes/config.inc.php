<?php
/**
 * Building Web Applications using MySQL and PHP (W1)
 *
 * HOE - Manipulating Graphics: configuration settings
 *
 */

// NOTE: These paths are suitable for use in PHP functions, but not in HTML
// Why?... echo them and you will see.
 
// Get the absolute path to the application root directory
$config['app_dir'] = dirname(dirname(__FILE__));

// For best results, use an absolute path to the thumbs directory
// This reduces potential for error when saving files.
$config['thumbs_dir'] = $config['app_dir'].'/thumbs/';
$config['uplodas_dir']= $config['app_dir'].'/uploads/';
?>