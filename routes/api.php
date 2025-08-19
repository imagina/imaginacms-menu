<?php

use Illuminate\Support\Facades\Route;
use Modules\Imenu\Http\Controllers\Api\MenuApiController;
use Modules\Imenu\Http\Controllers\Api\MenuItemApiController;
// add-use-controller



Route::prefix('/imenu/v1')->group(function () {
    Route::apiCrud([
      'module' => 'imenu',
      'prefix' => 'menus',
      'controller' => MenuApiController::class,
      'permission' => 'imenu.menus',
      'middleware' => ['index' => [], 'show' => []],
      // 'customRoutes' => [ // Include custom routes if needed
      //  [
      //    'method' => 'post', // get,post,put....
      //    'path' => '/some-path', // Route Path
      //    'uses' => 'ControllerMethodName', //Name of the controller method to use
      //    'middleware' => [] // if not set up middleware, auth:api will be the default
      //  ]
      // ]
    ]);
    Route::apiCrud([
      'module' => 'imenu',
      'prefix' => 'menuitems',
      'controller' => MenuItemApiController::class,
      'permission' => 'imenu.menuitems',
      'middleware' => ['index' => [], 'show' => []],
      // 'customRoutes' => [ // Include custom routes if needed
      //  [
      //    'method' => 'post', // get,post,put....
      //    'path' => '/some-path', // Route Path
      //    'uses' => 'ControllerMethodName', //Name of the controller method to use
      //    'middleware' => [] // if not set up middleware, auth:api will be the default
      //  ]
      // ]
    ]);
// append


});
