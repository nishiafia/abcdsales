<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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

/*Route::middleware('auth:api')->get('/companylist', function (Request $request) {
    return $request->user();
});

Route::get('companylist', 'API\CompanyController@index',function (Request $request) {
    return $request->user();
    LOG::info('miiiiiiiii' . Auth::id());
});*/


//Vue js Api Route

/*Route::apiResources([
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
    'communication' => 'API\CommunicationController'
]
);

Route::apiResources([
    'groupcode' => 'API\GroupcodeController'
]
);

Route::apiResources([
    'thanalist' => 'API\ThanaController'
]
);
Route::apiResources([
    'districtlist' => 'API\DistrictController'
]
);

Route::apiResources([
    'companylist' => 'API\CompanyController'
]
);
Route::apiResources([
    'product' => 'API\ProductController'
]
);

Route::apiResources([
    'expensecategory' => 'API\ExpensecategoryController'
]
);

Route::apiResources([
    'vendor' => 'API\VendorController'
]
);
Route::apiResources([
    'purchaseorder' => 'API\PurchaseorderController'
]
);
Route::apiResources([
    'purchaseitem' => 'API\PurchaseitemController'
]
);

Route::apiResources([
    'purchaseaccount' => 'API\PurchaseaccountController'
]
);

Route::apiResources([
    'discount' => 'API\DiscountController'
]
);
Route::apiResources([
    'tax' => 'API\TaxController'
]
);
Route::apiResources([
    'deliveryagent' => 'API\DeliveryagentController'
]
);
Route::apiResources([
    'salesorder' => 'API\SalesorderController'
]
);
Route::apiResources([
    'salesitem' => 'API\SalesorderitemController'
]
);

Route::apiResources([
    'salesaccount' => 'API\SalesorderaccountController'
]
);
Route::apiResources([
    'salesordercomment' => 'API\SalesordercommentController'
]
);
Route::apiResources([
    'variationlabel' => 'API\VariationlabelController'
]
);
Route::apiResources([
    'variation' => 'API\VariationController'
]
);*/




 //Route::group(['middleware' => 'auth:api'],function() {
  // LOG::info('apiauth=' . Auth::id());
 
    // \DB::enableQueryLog();
    // if (Auth::id()){
    //     LOG::info('sdsdsd' . Auth::id());
    // }
    //dump(\DB::getQueryLog());

    // Route::get('/companylist', function (Request $request) {
    //     LOG::info('$request =>' . $request);
    //     return $request->user();
    // });
  /*Route::resource('companylist', 'API\CompanyController')
  ->only(['index', 'store', 'show', 'edit', 'update','destroy'])
  ->middleware('useapiguard');



    Route::resource('user', 'API\UserController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('category', 'API\CategoryController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('productsize', 'API\ProductsizeController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('productcolor', 'API\ProductcolorController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('branch', 'API\BranchController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('customer', 'API\CustomerController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('communication', 'API\CommunicationController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('groupcode', 'API\GroupcodeController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('thanalist', 'API\ThanaController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('districtlist', 'API\DistrictController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('product', 'API\ProductController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('expensecategory', 'API\ExpensecategoryController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('vendor', 'API\VendorController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('purchaseorder', 'API\PurchaseorderController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('purchaseitem', 'API\PurchaseitemController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('purchaseaccount', 'API\PurchaseaccountController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('discount', 'API\DiscountController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('tax', 'API\TaxController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('deliveryagent', 'API\DeliveryagentController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('salesorder', 'API\SalesorderController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('salesitem', 'API\SalesorderitemController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('salesaccount', 'API\SalesorderaccountController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('salesordercomment', 'API\SalesordercommentController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('variationlabel', 'API\VariationlabelController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);

    Route::resource('variation', 'API\VariationController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);*/

//});


//Route::resource('login', 'LoginController');//('/login', 'LoginController');
