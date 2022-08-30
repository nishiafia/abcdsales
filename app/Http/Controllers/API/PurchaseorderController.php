<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Purchaseorder;
use App\Purchaseaccount;
use App\Purchaseitem;
use App\User;
use App\Company;
use App\Branch;
use App\Product;
use App\Variation;
use App\Purchasevariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PurchaseorderController extends Controller
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
        $data = Purchaseorder::with('vendordata')
                        ->where('systemid', $userinfo->systemid)->orderby('id', 'desc')->where('isactive',true)->paginate(20);

        return response()->json($data);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
        $data = Purchaseorder::with('vendordata')
                        ->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->orderby('id', 'desc')->where('isactive',true)->paginate(20);

        return response()->json($data);
        }
        if($userinfo->usertype == 'team')
        {
        $data = Purchaseorder::with('vendordata')
                        ->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->orderby('id', 'desc')->where('isactive',true)->paginate(20);

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
        $currentdate=date("Y-m-d");
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($currentdate <= $userinfo->subscriptiondate && $userinfo->subscriptionstatus === 1) {
        $this->validate($request, [
            'vendorid' => 'required',
            'totalamount' => 'required',
            'currentdate' => 'required',
            //'deliverydate' => 'required',
        ]);
        $maxid= Purchaseorder::max('id');
        Log::info( "maxid ===>" . $maxid);
        $orderid=$maxid+1;
        Log::info( "maxid11 ===>" . $orderid);
        $ordernumber='po-'.$orderid;
        if($request['deliverydate'] != '')
        {
            $deliverydate=date('Y-m-d', strtotime($request['deliverydate']));
        }
        else{
            $deliverydate=date('Y-m-d', strtotime($request['currentdate']));
        }
        if($request['duedeliverydate'] != '')
        {
            $duedate=date('Y-m-d', strtotime($request['duedeliverydate']));
        }
        else{
            $duedate=$deliverydate;
        }
        if($userinfo->entrylimit === 0)
        {
        $purchaseOrder = Purchaseorder::create([
            'ordernumber' => $ordernumber,
            'vendorid' => $request['vendorid'],
            'refpo' =>$request['refpo'],
            'notes' => $request['notes'],
            'systemid' => $request['systemid'],
            'entryid' => $request['entryid'],
            'companyid' => $request['companyid'],
            'branchid' => $request['branchid'],
            'totalamount' => $request['totalamount'],
            'paidamount' => $request['paidamount'],
            'dueamount' => $request['dueamount'],
            'totaltax' => $request['totaltax'],
            'totaldiscount' => $request['totaldiscount'],
            'shipping' => $request['shipping'],
            'currentdate' => date('Y-m-d', strtotime($request['currentdate'])),
            'deliverydate' => $deliverydate,
            'duedeliverydate' => $duedate,
         ]);

         $lastorderID = $purchaseOrder->id;
          if($request['paidamount']!=0)
          {
         $purchaseAccount= Purchaseaccount::create([
            'orderid' => $lastorderID,
            'systemid' => $request['systemid'],
            'entryid' => $request['entryid'],
            'paymentdate' => date('Y-m-d', strtotime($request['currentdate'])),
            'paymentmethod' => $request['paymentmethod'],
            'payamount' => $request['paidamount'],
            'dialcode' => $request['dialcode'],
            'bkashnumber' => $request['bkashnumber'],
            'bkashpin' => $request['bkashpin'],
            'cashpay' => $request['cashpay'],
            'bankid' => $request['bankid'],
            'accountholder' => $request['accountholder'],
            'accountnumber' => $request['accountnumber'],
            'branchname' => $request['branchname'],
         ]);
          }
         return $lastorderID;
        }
    else{
        $Ordercount = Purchaseorder::where('systemid', $request['systemid'])->whereDate('created_at',Carbon::today())->where('isactive', true)->count();
        Log::info( "Ordercount ===>" .$Ordercount);
            if($Ordercount < $userinfo->entrylimit){
                $purchaseOrder = Purchaseorder::create([
                    'ordernumber' => $ordernumber,
                    'vendorid' => $request['vendorid'],
                    'refpo' =>$request['refpo'],
                    'notes' => $request['notes'],
                    'systemid' => $request['systemid'],
                    'entryid' => $request['entryid'],
                    'companyid' => $request['companyid'],
                    'branchid' => $request['branchid'],
                    'totalamount' => $request['totalamount'],
                    'paidamount' => $request['paidamount'],
                    'dueamount' => $request['dueamount'],
                    'totaltax' => $request['totaltax'],
                    'totaldiscount' => $request['totaldiscount'],
                    'shipping' => $request['shipping'],
                    'currentdate' => date('Y-m-d', strtotime($request['currentdate'])),
                    'deliverydate' => $deliverydate,
                    'duedeliverydate' => $duedate,
                 ]);
                 $lastorderID = $purchaseOrder->id;
                  if($request['paidamount']!=0)
                  {
                 $purchaseAccount= Purchaseaccount::create([
                    'orderid' => $lastorderID,
                    'systemid' => $request['systemid'],
                    'entryid' => $request['entryid'],
                    'paymentdate' => date('Y-m-d', strtotime($request['currentdate'])),
                    'paymentmethod' => $request['paymentmethod'],
                    'payamount' => $request['paidamount'],
                    'dialcode' => $request['dialcode'],
                    'bkashnumber' => $request['bkashnumber'],
                    'bkashpin' => $request['bkashpin'],
                    'cashpay' => $request['cashpay'],
                    'bankid' => $request['bankid'],
                    'accountholder' => $request['accountholder'],
                    'accountnumber' => $request['accountnumber'],
                    'branchname' => $request['branchname'],
                 ]);
                  }
                 return $lastorderID;
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

    public function storeOrder(Request $request)
    {
        $orders = $request->all();
        Log::info( $orders);
        $req = $request->inputs;
        $lastorderID = $request->orderId;
        foreach ($req as $r) {
            Log::info( $r);
            //$labelid = Variation::where('id', $ci)->first();
            $gid = Product::where('id', $r['pitem'])->first();
          $itemdata=Purchaseitem::create([
                'orderid' =>    $lastorderID,
                'systemid' =>   $r['systemid'],
                'entryid' =>   $r['entryid'],
                'pitem' =>      $r['pitem'],
                'gcode' =>      $gid->groupid,
                'detail' =>     $r['detail'],
                'qty' =>        $r['qty'],
                'price' =>      $r['price'],
                'excat' =>      $r['excat'],
                'discountid'  =>  $r['discountid'],
                'taxid' =>        $r['taxid'],
                'total' =>      $r['total'],
                'discountfigure' =>      $r['discountfigure'],
                'taxfigure' =>      $r['taxfigure'],
                'delivered' =>  $r['delivered'],
            ]);
            $lastitemID = $itemdata->id;
            foreach ($r['variationsid'] as $r1) {
                $labelid = Variation::where('id', $r1)->first();
                Purchasevariation::create([
                    'orderid' =>    $lastorderID,
                    'peritemid' =>  $lastitemID,
                    'itemid' =>      $r['pitem'],
                    'vlabelid' =>     $labelid->vlabelid,
                    'variationid' =>     $r1,
                ]);

            }
        }
        return  $lastorderID;
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
        $orderdata = Purchaseorder::with('vendordata')
                                ->with('refporder')
                                ->with(array('companydata'=>function($query){
                                    $query->select('id','companyname');}))
                                ->where('systemid', $userinfo->systemid)
                                ->where('companyid', $userinfo->companyid)
                                ->where('isactive',true)
                                ->where('id', $id)
                                ->get();

         $itemdata = Purchaseitem::with('productCode')
                                ->with(array('inventory'=>function($query){
                                  $query->select('id','productcode','productname');}))
                                ->with(array('variationdatadetail.labeldata'=>function($query){
                                    $query->select('id','vlabel');}))
                                ->with(array('variationdatadetail.valdata'=>function($query){
                                        $query->select('id','variationname');}))
                                ->with('excategory')
                                ->where('systemid', $userinfo->systemid)
                                ->where('orderid', $id)
                                ->where('isactive',true)
                                ->where('returnitem',0)
                                ->get();
         $acountsdata = Purchaseaccount::where('systemid', $userinfo->systemid)
                                ->where('orderid', $id)
                                ->get();
        $jsonData = array("order" => $orderdata, "items" => $itemdata, "accounts"=> $acountsdata);
    	return response()->json($jsonData);
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
            'vendorid' => 'required',
            'deliverydate' => 'required',
        ]);
        $porder = Purchaseorder::findOrFail($id);
        $pretotalamount=$porder->totalamount-$porder->shipping;
        $totalamount=$pretotalamount+$request['shipping'];
        $porder->where('id', $id)->update(['totalamount' =>$totalamount
        ]);
        $porder->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $status = $request->header('StatusKey');
        $porder = Purchaseorder::findOrFail($id);
        if($status === 'delivery')
        {
            $porder->where('id', $id)->update(['orderdelivered' => 1]);
        }
        if($status === 'return')
        {
            $porder->where('id', $id)->update(['orderdelivered' => 2]);
        }
        if($status === 'cancel')
        {
            $porder->where('id', $id)->update(['orderdelivered' => 3]);
        }
    }
    public function getrefpo(Request $request,$id)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
        return Purchaseorder::orderby('id', 'asc')->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
            return Purchaseorder::orderby('id', 'asc')->where('vendorid', $id)->where('systemid', $userinfo->systemid)->get();
        }
        if($userinfo->usertype == 'team')
        {
            return Purchaseorder::orderby('id', 'asc')->where('vendorid', $id)->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->get();
        }
    }
    public function searchOrder(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
        $data = Purchaseorder::with('vendordata');
        if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
            $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
            $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
           //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
           $data->whereBetween('deliverydate', [$datefrom, $dateto]);
        }
        if($request['orderdelivered'] != "" ){
            $data->where('orderdelivered', $request['orderdelivered']);
       }


        //$data = Purchaseorder::with('vendordata')->$query
                        //->where('systemid', $request['systemid'])->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->get();
        $resp = $data->where('systemid', $userinfo->systemid)->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->paginate(20);
        return response()->json($resp);
    }
    if($userinfo->usertype == 'team')
        {
        $data = Purchaseorder::with('vendordata');
        if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
            $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
            $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
           //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
           $data->whereBetween('deliverydate', [$datefrom, $dateto]);
        }
        if($request['orderdelivered'] != "" ){
            $data->where('orderdelivered', $request['orderdelivered']);
       }

        //$data = Purchaseorder::with('vendordata')->$query
                        //->where('systemid', $request['systemid'])->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->get();
        $resp = $data->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->paginate();
        return response()->json($resp);
    }
    }
    public function purchaseOrderReport(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
        $data = Purchaseorder::with('vendordata');
        if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
            $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
            $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
           //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
           $data->whereBetween('created_at', [$datefrom, $dateto]);
        }
        if($request['orderdelivered'] != "" ){
            $data->where('orderdelivered', $request['orderdelivered']);
       }
       if($request['vendorid'] != "" ){
        $data->where('vendorid', $request['vendorid']);
       }
       if($request['orderid'] != "" ){
        $data->where('id', $request['orderid']);
       }


        //$data = Purchaseorder::with('vendordata')->$query
                        //->where('systemid', $request['systemid'])->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->get();
        $resp = $data->where('systemid', $userinfo->systemid)->orderby('id', 'desc')->where('isactive',true)->get();
        return response()->json($resp);
    }
    if($userinfo->usertype == 'team')
        {
        $data = Purchaseorder::with('vendordata');
        if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
            $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
            $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
           //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
           $data->whereBetween('deliverydate', [$datefrom, $dateto]);
        }
        if($request['orderdelivered'] != "" ){
            $data->where('orderdelivered', $request['orderdelivered']);
       }

        //$data = Purchaseorder::with('vendordata')->$query
                        //->where('systemid', $request['systemid'])->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->get();
        $resp = $data->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->paginate();
        return response()->json($resp);
    }
    }

    public function searchOutgoingPayment(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        $search=$request['inputValue'];

        Log::info( "searchvalue ===>" . $search);
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
        $data = Purchaseorder::select('purchaseorders.*','customers.customername')
        ->join('customers', 'purchaseorders.vendorid', '=', 'customers.id')
        ->where(function($q) use ($search) {
            $q->where('purchaseorders.ordernumber', 'like', '%' . $search . '%')
                ->orWhere('customers.customername', 'like', '%' . $search . '%')
                ->orWhere('customers.telephone', 'like', '%' . $search .'%') ;
        });
    if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
        $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
        $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
       //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
       $data->whereBetween('purchaseorders.deliverydate', [$datefrom, $dateto]);
    }
    $resp = $data->where('purchaseorders.systemid', $userinfo->systemid)
                 ->where('purchaseorders.companyid', $userinfo->companyid)
                 ->orderby('purchaseorders.id', 'desc')
                 ->where('purchaseorders.isactive',true)
                 ->get();
        return response()->json($resp);
     }
     if($userinfo->usertype == 'team')
        {

            $data = Purchaseorder::select('purchaseorders.*','customers.customername')
            ->join('customers', 'purchaseorders.vendorid', '=', 'customers.id')
            ->where(function($q) use ($search) {
                $q->where('purchaseorders.ordernumber', 'like', '%' . $search . '%')
                    ->orWhere('customers.customername', 'like', '%' . $search . '%')
                    ->orWhere('customers.telephone', 'like', '%' . $search .'%') ;
            });
        /*$data = Salesorder::with(array('customerdata'=>function($query){
            $query->where('customername', 'LIKE', "%$search%");}))*/
        if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
            $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
            $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
           //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
           $data->whereBetween('purchaseorders.deliverydate', [$datefrom, $dateto]);
        }


        //$data = Purchaseorder::with('vendordata')->$query
                        //->where('systemid', $request['systemid'])->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->get();
        //$resp = $data->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('customerid', $request['customerid'])->orderby('id', 'desc')->where('isactive',true)->get();
        $resp = $data->where('purchaseorders.systemid', $userinfo->systemid)
                     ->where('purchaseorders.companyid', $userinfo->companyid)
                     ->orderby('purchaseorders.id', 'desc')
                     ->where('purchaseorders.isactive',true)
                     ->get();
        return response()->json($resp);
     }
    }

    public function createOutgoingPayment(Request $request)
    {

       //$orders = $request->all();
      // Log::info($orders);
        $req = $request->ordersList;
        $accountinfo = $request['accountInfo'];
       // Log::info( $accountinfo );

        foreach ($req as $r) {
            Log::info( $r);

        $orderdata = Purchaseorder::findOrFail($r['id']);
        $salesAccount= Purchaseaccount::create([
           'orderid' => $r['id'],
           'systemid' => $accountinfo['systemid'],
           'entryid' =>   $accountinfo['entryid'],
           'paymentdate' => date('Y-m-d', strtotime($accountinfo['paymentdate'])),
           'paymentmethod' => $accountinfo['paymentmethod'],
           'payamount' => $r['payable'],
           'dialcode' => $accountinfo['dialcode'],
           'bkashnumber' => $accountinfo['bkashnumber'],
           'bkashpin' => $accountinfo['bkashpin'],
           'cashpay' => $accountinfo['cashpay'],
           'bankid' => $accountinfo['bankid'],
           'accountholder' => $accountinfo['accountholder'],
           'accountnumber' => $accountinfo['accountnumber'],
           'branchname' => $accountinfo['branchname'],
           'chequenumber' => $accountinfo['chequenumber'],
        ]);
       // $salesAccountsumamount=Salesorderaccount::where('orderid', $r['id'])->where('isactive',true)->sum('paidamount');
        $purchaseAccountsumamount=$orderdata->paidamount+$r['payable'];
        $dueamount=  $orderdata->totalamount-$purchaseAccountsumamount;

        $orderdata->where('id', $r['id'])->update(['paidamount' =>$purchaseAccountsumamount,'dueamount' =>$dueamount]);
    }
    return response()->json('success');
    }
    public function getordernumber(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Purchaseorder::orderby('id', 'asc')->select('id','ordernumber')->where('systemid', $userinfo->systemid)->where('isactive',1)->get();
        }
        if($userinfo->usertype == 'team'){
            return Purchaseorder::orderby('id', 'asc')->select('id','ordernumber')->where('systemid', $userinfo->systemid)->where('isactive',1)->get(); 
        }
    }
}
