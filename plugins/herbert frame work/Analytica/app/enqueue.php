<?php namespace Analytica;

/** @var \Herbert\Framework\Enqueue $enqueue */

$enqueue->admin([
    'as'     => 'jquery',
    'src'    => 'jquery',
    'filter' => [ 'panel' => 'analytica-admin-settings' ]
]);

$enqueue->admin([
    'as'  => 'popup',
    'src' => Helper::assetUrl('/jquery/popup.js'), // => /resources/assets/js/popup.js
], 'footer');

$enqueue->admin([
    'as'  => 'analytica',
    'src' => Helper::assetUrl('/jquery/analytica.js'), // => /resources/assets/js/analytica.js
	'filter' => ['panel'=>'analytica-admin-settings']
	
], 'footer');

$enqueue->admin([
    'as'     => 'jquery-ui-datepicker',
    'src'    => 'jquery-ui-datepicker',
    'filter' => [ 'panel' => 'analytica-admin-settings' ]
], 'footer');

$enqueue->admin([
    'as'     => 'jquery-ui-datepicker',
    'src'    => 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css',
    'filter' => [ 'panel' => 'analytica-admin-settings' ]
]);

$enqueue->admin([
    'as'     => 'datepicker',
    'src'    => Helper::assetUrl('/jquery/datepicker.js'),
    'filter' => [ 'panel' => 'analytica-admin-settings' ]
], 'footer');
