<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/getuser', 'API\UserController@getuser');
Route::post('/vuelogin', 'Auth\LoginController@vuelogin');
Route::post('/vueloginadmin', 'Auth\LoginController@vueloginadmin');
Route::post('/adminuserlogin', 'Auth\LoginController@adminuserlogin');
Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/logout', 'Auth\LoginController@logout');

Route::get('/getcompany', 'API\UserController@getcompany');
Route::get('/getbranchcontactperson', 'API\UserController@getbranchcontactperson');
Route::get('/getbusinesscategory', 'API\CategoryController@getbusinesscategory');
Route::post('/updateprofile/{id}', 'API\UserController@updateprofile');
Route::get('/getcity', 'API\ThanaController@getcity');
Route::get('/getdis', 'API\DistrictController@getdistrict');
Route::get('/getcompanytype', 'API\ThanaController@getcompanytype');
Route::get('/getteamcompany', 'API\CompanyController@getteamcompany');
Route::get('/getteam', 'API\UserController@getteam');
Route::get('/getpartner', 'API\UserController@getpartner');

Route::get('/getpartnertype', 'API\ThanaController@getpartnertype');
Route::get('/getgroupcode', 'API\GroupcodeController@getgroupcode');
Route::get('/getunittype', 'API\GroupcodeController@getunittype');
Route::get('/getproductcolor', 'API\ProductcolorController@getproductcolor');
Route::get('/getproductsize', 'API\ProductsizeController@getproductsize');
Route::get('/getproducttype', 'API\ProductcolorController@getproducttype');
Route::get('/getvendor', 'API\CustomerController@getvendor');
Route::get('/getactivityvendor', 'API\CustomerController@getactivityvendor');
Route::post('/createpurchaseorder', 'API\PurchaseorderController@store');
Route::post('/saveOrderItem', 'API\PurchaseorderController@storeOrder');

Route::get('/getitem', 'API\ProductController@getitem');
Route::get('/getsalesitem', 'API\ProductController@getsalesitem');
Route::get('/getexcat', 'API\ExpensecategoryController@getexcat');
Route::get('/getbank', 'API\ExpensecategoryController@getbank');
Route::get('/getdiscount', 'API\DiscountController@getdiscount');
Route::get('/gettax', 'API\TaxController@gettax');

Route::get('/getpaymentmethodinformation/{id}', 'API\PurchaseaccountController@getpaymentmethodinformation');

Route::post('/updateItemDelivery', 'API\PurchaseitemController@updateItemDelivery');
Route::get('/getrefpo/{id}', 'API\PurchaseorderController@getrefpo');

Route::post('/searchOrder', 'API\PurchaseorderController@searchOrder');
Route::get('/getadminuser', 'API\UserController@getadminuser');

Route::post('/searchProduct', 'API\ProductController@searchProduct');
Route::post('/searchInventory', 'API\ProductController@searchInventory');
Route::get('/businesscategory', 'API\CategoryController@businesscategory');

Route::get('/getpurchaseprice/{id}', 'API\ProductController@getpurchaseprice');
Route::get('/getcustomer', 'API\CustomerController@getcustomer');
Route::get('/getrefso/{id}', 'API\SalesorderController@getrefso');
Route::get('/getagent', 'API\DeliveryagentController@getagent');
Route::get('/getsalesprice/{id}', 'API\ProductController@getsalesprice');
Route::get('/getdeliveryprice/{id}', 'API\DeliveryagentController@getdeliveryprice');

Route::post('/createsalesorder', 'API\SalesorderController@store');
Route::post('/saveSalesOrderItem', 'API\SalesorderController@storeOrder');
Route::post('/updateSalesItemDelivery', 'API\SalesorderitemController@updateItemDelivery');

Route::get('/getsalespaymentmethodinforsearchUsermation/{id}', 'API\SalesorderaccountController@getpaymentmethodinformation');
Route::post('/searchSalesOrder', 'API\SalesorderController@searchOrder');
Route::get('/getbranch/{id}', 'API\BranchController@getbranch');
Route::post('/createteam', 'API\UserController@storeTeam');
Route::post('/saveTeamCompany', 'API\UserController@saveTeamCompany');
Route::get('/getswitchcompany', 'API\UserController@getswitchcompany');

Route::get('/updateSwitchCompany/{id}', 'API\UserController@updateSwitchCompany');
Route::get('/getvariationlabel', 'API\VariationlabelController@getvariationlabel');
Route::get('/getvariation', 'API\VariationController@getvariation');
Route::post('/createInventoryVariation', 'API\ProductController@createInventoryVariation');
Route::get('/getvariationlist/{id}', 'API\ProductController@getvariationlist');
Route::post('/searchPartner', 'API\CustomerController@searchPartner');

Route::post('/searchIncomingPayment', 'API\SalesorderController@searchIncomingPayment');
Route::post('/createIncomingPayment', 'API\SalesorderController@createIncomingPayment');
Route::post('/searchOutgoingPayment', 'API\PurchaseorderController@searchOutgoingPayment');
Route::post('/createOutgoingPayment', 'API\PurchaseorderController@createOutgoingPayment');
Route::post('/searchUser', 'API\UserController@searchUser');
Route::post('/changeUserPassword', 'API\UserController@changeUserPassword');
Route::post('/changeUserPasswordadmin', 'API\UserController@changeUserPasswordAdmin');
Route::get('/getcompany', 'API\CompanyController@getcompany');
Route::get('/getinventorygroup', 'API\GroupcodeController@getinventorygroup');
Route::post('/purchaseOrderReport', 'API\PurchaseorderController@purchaseOrderReport');
Route::post('/getordernumber', 'API\PurchaseorderController@getordernumber');
Route::get('/getcustomercommunication/{id}', 'API\CommunicationController@getcustomercommunication');

Route::post('/changeUserPasswordadmin1', 'API\UserController@changeUserPasswordAdmin1');

Route::get('/test', function() {
    return auth::user();
});

Route::get('/', function () {
    //return view('welcome');
    return view('master');
});


//Auth::routes();
