<?php


return [

    /**
	* Plugin Name
	*/
	'pluginName' => 'Analytica',
	
	/**
	* Plugin Table Prefix
	*/
	'prefix' => 'analytica_',
	
    /**
     * The Herbert version constraint.
     */
    'constraint' => '~0.9.9',

    /**
     * Auto-load all required files.
     */
    'requires' => [
        __DIR__ . '/app/customPostTypes.php',
		__DIR__ . '/app/actions.php'
    ],
    
    /**
     * The tables to manage.
     */
    'tables' => [
    ],


    /**
     * Activate
     */
    'activators' => [
        __DIR__ . '/app/activate.php'
    ],

    /**
     * Activate
     */
    'deactivators' => [
        __DIR__ . '/app/deactivate.php'
    ],

    /**
     * The shortcodes to auto-load.
     */
    'shortcodes' => [
        __DIR__ . '/app/shortcodes.php'
    ],

    /**
     * The widgets to auto-load.
     */
    'widgets' => [
        __DIR__ . '/app/widgets.php'
    ],

    /**
     * The widgets to auto-load.
     */
    'enqueue' => [
        __DIR__ . '/app/enqueue.php'
    ],

    /**
     * The routes to auto-load.
     */
    'routes' => [
        'Analytica' => __DIR__ . '/app/routes.php',
    ],

    /**
     * The panels to auto-load.
     */
    'panels' => [
        'Analytica' => __DIR__ . '/app/panels.php',
    ],

    /**
     * The APIs to auto-load.
     */
    'apis' => [
        'Analytica' => __DIR__ . '/app/api.php',
    ],

    /**
     * The view paths to register.
     *
     * E.G: 'Analytica' => __DIR__ . '/views'
     * can be referenced via @Analytica/
     * when rendering a view in twig.
     */
    'views' => [
        'Analytica' => __DIR__ . '/resources/views'
    ],

    /**
     * The view globals.
     */
    'viewGlobals' => [

    ],

    /**
     * The asset path.
     */
    'assets' => '/resources/assets/'

];
