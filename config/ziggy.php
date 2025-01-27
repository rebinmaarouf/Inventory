<?php

return [
  /**
   * Enable or disable Ziggy.
   */
  'enabled' => true,

  /**
   * The base URL of your application.
   */
  'url' => env('APP_URL', 'http://localhost'),

  /**
   * The routes to include in the Ziggy output.
   */
  'routes' => [
    // Include all routes
    '*',
  ],

  /**
   * The path to the Ziggy Blade directive.
   */
  'blade' => [
    'path' => 'vendor/tightenco/ziggy/src/js/ziggy.js',
  ],

  /**
   * Whitelist specific routes (optional).
   */
  'whitelist' => ['socialite.auth'],
];
