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
);

 /*Route::group(['middleware' => [ 'api' ]],function() {
   // \DB::enableQueryLog();
    // if (Auth::id()){
    //     LOG::info('sdsdsd' . Auth::id());
    // }
    //dump(\DB::getQueryLog());
   Route::resource('companylist', 'API\CompanyController')
    ->only(['index', 'store', 'show', 'edit', 'update','destroy']);;
});*/


//Route::resource('login', 'LoginController');//('/login', 'LoginController');
