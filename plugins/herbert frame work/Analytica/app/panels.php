<?php namespace Analytica;

/** @var \Herbert\Framework\Panel $panel */


$panel->add([
    'type' => 'panel',
	'as'   => 'mainPanel',
	'title' => 'Analytica',
	'rename' => 'General',
	'slug' => 'analytica-admin-settings',
	'icon' => 'dashicons-chart-area',
	'uses' => __NAMESPACE__ . '\Controllers\AnalyticaController@index',
	'post' => [
	    'save-accesscode' => __NAMESPACE__ . '\Controllers\AnalyticaController@index',
	    'save-settings' => __NAMESPACE__ . '\Controllers\AnalyticaController@save',
		'status-change' => __NAMESPACE__ . '\Controllers\AnalyticaController@postStatusChange',
		'nextpage' => __NAMESPACE__ . '\Controllers\AnalyticaController@nextpage',
	]
]);


/*  
$panel->add([
    'type'   => 'wp-sub-panel',
	'parent' => 'plugins.php',
	'as' => 'dashboardSubpanel',
	'title' => 'Analytica SubPanel',
	'slug' => 'plugins.php',
	'uses' => __NAMESPACE__ . '\Controllers\AnalyticaController@index'
]); */
