<?php
/*
  Plugin Name: PIP Mode
  Plugin URI:
  Plugin Description: Adding PIP Mode for questions
  Plugin Version: 1.0
  Plugin Date: 2022-03-30
  Plugin Author: SHAIK MASTHAN
  Plugin Author URI:
  Plugin License: GPLv2
  Plugin Minimum Question2Answer Version:
  Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;   
}

qa_register_plugin_layer('pipmode-layer.php', 'Adding PIP Button and code to the Question');
