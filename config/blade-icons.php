<?php

return [

	/*
    |--------------------------------------------------------------------------
    | Icons Sets
    |--------------------------------------------------------------------------
    |
    | With this config option you can define a couple of
    | default icon sets. Provide a key name for your icon
    | set and a combination from the options below.
    |
    */

	'sets' => [
		'rounded-filled-400' => [
			'path' => 'resources/svg/icons/rounded/filled/400',
			'prefix' => 'icon',
		],

		'rounded-filled-200' => [
			'path' => 'resources/svg/icons/rounded/filled/200',
			'prefix' => 'icon.200',
		],

		'rounded-stroked-400' => [
			'path' => 'resources/svg/icons/rounded/stroked/400',
			'prefix' => 'icon.s',
		],

		'rounded-stroked-200' => [
			'path' => 'resources/svg/icons/rounded/stroked/200',
			'prefix' => 'icon.s.200',
		],
	],

	/*
    |--------------------------------------------------------------------------
    | Global Default Classes
    |--------------------------------------------------------------------------
    |
    | This config option allows you to define some classes which
    | will be applied by default to all icons.
    |
    */

	'class' => 'svg-icon',

	/*
    |--------------------------------------------------------------------------
    | Global Default Attributes
    |--------------------------------------------------------------------------
    |
    | This config option allows you to define some attributes which
    | will be applied by default to all icons.
    |
    */

	'attributes' => [
		// 'width' => 50,
		// 'height' => 50,
	],

	/*
    |--------------------------------------------------------------------------
    | Global Fallback Icon
    |--------------------------------------------------------------------------
    |
    | This config option allows you to define a global fallback
    | icon when an icon in any set cannot be found. It can
    | reference any icon from any configured set.
    |
    */

	'fallback' => '',

	/*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | These config options allow you to define some
    | settings related to Blade Components.
    |
    */

	'components' => [

		/*
        |----------------------------------------------------------------------
        | Disable Components
        |----------------------------------------------------------------------
        |
        | This config option allows you to disable Blade components
        | completely. It's useful to avoid performance problems
        | when working with large icon libraries.
        |
        */

		'disabled' => false,

		/*
        |----------------------------------------------------------------------
        | Default Icon Component Name
        |----------------------------------------------------------------------
        |
        | This config option allows you to define the name
        | for the default Icon class component.
        |
        */

		'default' => 'icon',

	],

];
