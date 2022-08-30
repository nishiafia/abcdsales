<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Product;
use App\Company;
use App\Branch;
use App\User;
use App\Inventoryvariation;
use App\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
        $data = Product::with('groupname')
                        ->with('punittype')
                        ->with(array('companydata'=>function($query){
                            $query->select('id','companyname');}))
                        ->orderby('id', 'desc')->paginate(20);

        return response()->json($data);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            $data = Product::with('groupname')
            ->with('punittype')
            ->with(array('companydata'=>function($query){
                $query->select('id','companyname');}))
            ->with('variationdata')
            ->where('systemid', $userinfo->systemid)->orderby('id', 'desc')->paginate(15);

            return response()->json($data);
        }
        if($userinfo->usertype == 'team'){
            $data = Product::with('groupname')
            ->with('punittype')
            ->with(array('companydata'=>function($query){
                $query->select('id','companyname');}))
            ->with('variationdata')
            ->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->orderby('id', 'desc')->paginate(15);

            return response()->json($data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'groupid' => 'required',
            'productname' => 'required',
            'productunit' => 'required',
            'details' => 'required',
            'unittype' => 'required',
            'productcost' => 'required',
            'sellprice' => 'required',
        ]);

        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();

        $maxid= Product::max('pid');
        $pid=$maxid+1;
        $productcode='sku-'.$pid;
        Log::info( "pur ===>" . $request['purchaseproduct']);
        Log::info( "sales ===>" . $request['salesproduct']);
        if($request['purchaseproduct'] == 0 && $request['salesproduct'] == 0)
        {
            $request['purchaseproduct']=2;
            Log::info( "pur1 ===>" . $request['purchaseproduct']);
            Log::info( "sales1 ===>" . $request['salesproduct']);
        }
        $currentdate=date("Y-m-d");
        $prcode = Product::where('groupid', $request['groupid'])->where('productname', $request['productname'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
        if(!$prcode)
        {
           if($currentdate <= $userinfo->subscriptiondate && $userinfo->subscriptionstatus === 1) {
             if($userinfo->entrylimit === 0)
             {
              //  Log::info( "Customercount ===>" .$Customercount);
                $inventory= Product::create([
                    'pid' => $pid,
                    'productcode' => $productcode,
                    'groupid' => $request['groupid'],
                    'productname' => $request['productname'],
                    'details' => $request['details'],
                    'productunit' => $request['productunit'],
                    'unittype' => $request['unittype'],
                    'productcost' => $request['productcost'],
                    'sellprice' => $request['sellprice'],
                    'purchaseproduct' => $request['purchaseproduct'],
                    'salesproduct' => $request['salesproduct'],
                    'systemid' => $request['systemid'],
                    'entryid' => $request['entryid'],
                    'companyid' => $request['companyid'],
                    'branchid' => $request['branchid'],
                ]);
              return  $inventoryID = $inventory->id;
            }
            else{
                $totalcount = Product::where('systemid', $request['systemid'])->whereDate('created_at',Carbon::today())->where('isactive', true)->count();
                Log::info( "totalcount ===>" .$totalcount);
                Log::info( "entrylimit ===>" .$userinfo->entrylimit);
                if($totalcount <$userinfo->entrylimit){
                    $inventory= Product::create([
                        'pid' => $pid,
                        'productcode' => $productcode,
                        'groupid' => $request['groupid'],
                        'productname' => $request['productname'],
                        'details' => $request['details'],
                        'productunit' => $request['productunit'],
                        'unittype' => $request['unittype'],
                        'productcost' => $request['productcost'],
                        'sellprice' => $request['sellprice'],
                        'purchaseproduct' => $request['purchaseproduct'],
                        'salesproduct' => $request['salesproduct'],
                        'systemid' => $request['systemid'],
                        'entryid' => $request['entryid'],
                        'companyid' => $request['companyid'],
                        'branchid' => $request['branchid'],
                    ]);
                    return  $inventoryID = $inventory->id;
                }
                else{
                    return response()->json([
                        'message' => 'not'
                       ]);
                }
            }
         }
         else{
            return response()->json([
                'message' => 'expired'
               ]);
        }
      }
    }
    public function createInventoryVariation(Request $request)
    {
        $lastinventoryID = $request->inventoryId;
        Inventoryvariation::where('inventoryid', $lastinventoryID)->delete();
        //$req = $request->all();
       // Log::info( $req);
        $variationInfo = $request->variationInfo;
        Log::info( "lastinventoryID ===>" . $lastinventoryID);
        foreach ($variationInfo as $ci) {
            Log::info( "variationInfo Areay". $ci );
            $labelid = Variation::where('id', $ci)->first();
            $variationcreate= Inventoryvariation::create([
                'inventoryid' => $lastinventoryID,
                'variationid' => $ci,
                'vlabelid' => $labelid->vlabelid,
             ]);
        }
        return  $lastinventoryID;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        $data = Product::with('groupname')
                        ->with('punittype')
                        ->with(array('companydata'=>function($query){
                            $query->select('id','companyname');}))
                       ->with(array('variationdatadetail.labeldata'=>function($query){
                                $query->select('id','vlabel');}))
                       ->with(array('variationdatadetail.valdata'=>function($query){
                                    $query->select('id','variationname');}))
                      //  ->with('labeldata1')
                        ->where('systemid', $userinfo->systemid)
                        ->where('companyid', $userinfo->companyid)
                        ->where('id', $id)
                        ->get();

    	return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'groupid' => 'required',
            'productname' => 'required',
            'productunit' => 'required',
            'details' => 'required',
            'unittype' => 'required',
            'productcost' => 'required',
            'sellprice' => 'required',
        ]);
        if($request['purchaseproduct'] == 0 && $request['purchaseproduct'] == 0)
        {
            $request['purchaseproduct']=2;
            Log::info( "pur ===>" . $request['purchaseproduct']);
            Log::info( "sales ===>" . $request['salesproduct']);
        }

        $product = Product::findOrFail($id);
        if($request['groupid'] != $product->groupid || $request['productname'] != $product->productname)
        {
            Log::info( "enter ===>" . $request['salesproduct']);
            $prcode = Product::where('groupid', $request['groupid'])->where('productname', $request['productname'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first(); 
            if(!$prcode){
                Log::info( "enter1 ===>" . $request['productname']);
                $product->update($request->all());
                return response()->json('new');
            }
        }
    else{
        $product->update($request->all());
        return response()->json('same');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getitem(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
        return Product::with(array('groupname'=>function($query){
            $query->select('id','gcode','gtitle');
        }))->select('id','groupid','productname')->orderby('id', 'asc')->where('purchaseproduct',2)->where('isactive',true)->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Product::with(array('groupname'=>function($query){
                $query->select('id','gcode','gtitle');
            }))->select('id','groupid','productname')->orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('purchaseproduct',2)->where('isactive',true)->get();
        }
        if($userinfo->usertype == 'team'){
            return Product::with(array('groupname'=>function($query){
                $query->select('id','gcode','gtitle');
            }))->select('id','groupid','productname')->orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('purchaseproduct',2)->where('isactive',true)->get();
        }
    }
    public function getsalesitem(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
        return Product::with(array('groupname'=>function($query){
            $query->select('id','gcode','gtitle');
        }))->select('id','groupid','productname')->orderby('id', 'asc')->where('salesproduct',3)->where('isactive',true)->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Product::with(array('groupname'=>function($query){
                $query->select('id','gcode','gtitle');
            }))->select('id','groupid','productname')->orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('salesproduct',3)->where('isactive',true)->get();
        }
        if($userinfo->usertype == 'team'){
            return Product::with(array('groupname'=>function($query){
                $query->select('id','gcode','gtitle');
            }))->select('id','groupid','productname')->orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('salesproduct',3)->where('isactive',true)->get();
        }
    }


    public function searchProduct(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        $data = Product::with('groupname')
                         ->with('punittype');
        Log::info( "pur ===>" . $request['purchaseproduct']);
        Log::info( "sales ===>" . $request['salesproduct']);
        if($userinfo->usertype == 'superadmin')
        {
        $data = Product::with('groupname')
                        ->with('punittype')
                        ->where('purchaseproduct',$request['purchaseproduct'])
                        ->orderby('id', 'desc')->get();

        return response()->json($data);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            if($request['purchaseproduct'] != 0)
            {
               $data->where('purchaseproduct',$request['purchaseproduct']);
            }
            if($request['salesproduct'] != 0)
            {
               $data->where('salesproduct',$request['salesproduct']);
            }

            $resp = $data->where('systemid', $userinfo->systemid)
                         ->where('groupid', $request['groupid'])
                          ->orderby('id', 'desc')
                          ->paginate(15);

            return response()->json($resp);
        }
        if($userinfo->usertype == 'team'){
            if($request['purchaseproduct'] != 0)
            {
               $data->where('purchaseproduct',$request['purchaseproduct']);
            }
            if($request['salesproduct'] != 0)
            {
               $data->where('salesproduct',$request['salesproduct']);
            }

            $resp = $data->where('systemid', $userinfo->systemid)
                             ->where('companyid', $userinfo->companyid)
                         ->where('groupid', $request['groupid'])
                          ->orderby('id', 'desc')
                          ->paginate(15);

            return response()->json($resp);
        }
    }
    public function searchInventory(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        $data = Product::with('groupname')
                         ->with('companydata')
                         ->with('punittype');

        if($userinfo->usertype == 'superadmin')
        {
        $data = Product::with('groupname')
                        ->with('punittype')
                        //->where('purchaseproduct',$request['purchaseproduct'])
                        ->orderby('id', 'desc')->get();

        return response()->json($data);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            if($request['startdate'] != "" && $request['enddate'] != ""){
                $datefrom=date('Y-m-d', strtotime($request['startdate']));
                $dateto=date('Y-m-d', strtotime($request['enddate']));
               //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
               $data->whereBetween('created_at', [$datefrom, $dateto]);
            }
            if($request['companyid'] != ""){
               $data->where('companyid', $request['companyid']);
            }
            if($request['groupid'] != ""){
               $data->where('groupid', $request['groupid']);
            }

            $resp = $data->where('systemid', $userinfo->systemid)
                         //->where('groupid', $request['groupid'])
                          ->orderby('id', 'desc')
                          //->paginate(15);
                          ->get();

            return response()->json($resp);
        }
        if($userinfo->usertype == 'team'){
            if($request['startdate'] != "" && $request['enddate'] != ""){
                $datefrom=date('Y-m-d', strtotime($request['startdate']));
                $dateto=date('Y-m-d', strtotime($request['enddate']));
               //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
               $data->whereBetween('created_at', [$datefrom, $dateto]);
            }
            if($request['groupid'] != ""){
                $data->where('groupid', $request['groupid']);
             }

            $resp = $data->where('systemid', $userinfo->systemid)
                             ->where('companyid', $userinfo->companyid)
                         //->where('groupid', $request['groupid'])
                          ->orderby('id', 'desc')
                          //->paginate(15);
                          ->get();

            return response()->json($resp);
        }
    }

    public function getpurchaseprice(Request $request,$id)
    {
        $systemid = base64_decode($request->header('sessionKey'));

        $utype = User::where('id', $systemid)->first();
            return Product::select('productcost')->where('id', $id)->where('systemid', $utype->systemid)->get();
    }
    public function getsalesprice(Request $request,$id)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $utype = User::where('id', $systemid)->first();
            return Product::select('sellprice')->where('id', $id)->where('systemid', $utype->systemid)->get();
    }

    public function getvariationlist(Request $request,$id)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $utype = User::where('id', $systemid)->first();
            return Inventoryvariation::with(array('labeldata'=>function($query){
                $query->select('id','vlabel');
            }))->with(array('valdata'=>function($query){
                $query->select('id','variationname');
            }))->orderby('vlabelid', 'asc')->where('inventoryid', $id)->get();
    }
}
