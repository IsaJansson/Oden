<?php
/*
* Helpers for the template file.
*/

$oden->data['header'] = '<h1>Header: Hi I\'m Oden</h1>';
$oden->data['main'] = '<p>Main: Now with a theme engine, not much to report for now.</p>';
$oden->data['footer'] = '<p>Footer: &copy; Oden by Isa Jansson </p>';

// Ptint debug information from the framework.
function get_debug() {
	$oden = COden::Instance();
	$html = "<h2>Debuginformation</h2><hr><p>The content of the config array:</p><pre>" .
	htmlentities(print_r($oden->config, true)) . "</pre>";
	$html .= "<hr><p>The content of the data array:</p><pre>" . htmlentities(print_r($oden->data, true)) . "</pre>";
	$html .= "<hr><p>The content of the request array:</p><pre>" . htmlentities(print_r($oden->request, true)) . "</pre>";
	return $html;
}