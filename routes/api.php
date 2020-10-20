<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Vue js Api Route

Route::apiResources([
        'user' => 'API\UserController'
    ]
);
Route::apiResources([
    'category' => 'API\CategoryController'
]
);

Route::apiResources([
    'productsize' => 'API\ProductsizeController'
]
);

Route::apiResources([
    'productcolor' => 'API\ProductcolorController'
]
);

Route::apiResources([
    'branch' => 'API\BranchController'
]
);

Route::apiResources([
    'customer' => 'API\CustomerController'
]
);

Route::apiResources([
    'groupcode' => 'API\GroupcodeController'
]
);


//Route::resource('login', 'LoginController');//('/login', 'LoginController');
