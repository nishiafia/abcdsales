<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Salesorder;
use App\Salesorderaccount;
use App\Salesorderitem;
use App\User;
use App\Company;
use App\Branch;
use App\Salesordercomment;
use App\Product;
use App\Variation;
use App\Salesvariation;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SalesorderController extends Controller
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
        $data = Salesorder::with('customerdata')
                        ->where('systemid', $userinfo->systemid)->orderby('id', 'desc')->where('isactive',true)->paginate(10);

        return response()->json($data);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {  
            
         $data = Salesorder::select('salesorders.*','customers.customername')
                                ->join('customers', 'salesorders.customerid', '=', 'customers.id')
                             ->where('salesorders.systemid', $userinfo->systemid)
                             ->where('salesorders.companyid', $userinfo->companyid)
                             ->orderby('salesorders.id', 'desc')
                             ->where('salesorders.isactive',true)
                             ->paginate(20);

        return response()->json($data);
        }
        if($userinfo->usertype == 'team')
        {  
        $data = Salesorder::select('salesorders.*','customers.customername')
                                ->join('customers', 'salesorders.customerid', '=', 'customers.id')
                            ->where('salesorders.systemid', $userinfo->systemid)
                            ->where('salesorders.companyid', $userinfo->companyid)
                            ->orderby('salesorders.id', 'desc')
                            ->where('salesorders.isactive',true)
                            ->paginate(20);

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
            'customerid' => 'required',
            'agentid' => 'required',
            'totalamount' => 'required',
            'currentdate' => 'required',
           // 'deliverydate' => 'required',
        ]);
        $maxid= Salesorder::max('id');
        Log::info( "maxid ===>" . $maxid); 
        $orderid=$maxid+1;
        Log::info( "maxid11 ===>" . $orderid); 
        $ordernumber='so-'.$orderid;
        if($request['deliverydate'] != '')
        {
            $deliverydate=date('Y-m-d', strtotime($request['deliverydate']));
        }
        else{
            $time = strtotime('0000-00-00');
            $deliverydate=NULL;//date('Y-m-d', $time);
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
        
        $salesOrder = Salesorder::create([
            'ordernumber' => $ordernumber,
            'customerid' => $request['customerid'],
            'refso' =>$request['refso'],
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
            'agentid' => $request['agentid'],
            'deliveryarea' => $request['deliveryarea'],
            'codcharge' => $request['codcharge'],
            'shipping' => $request['shipping'],
            'currentdate' => date('Y-m-d', strtotime($request['currentdate'])),
            'deliverydate' => $deliverydate,
            'duedeliverydate' =>$duedate,
         
         ]);

         $lastorderID = $salesOrder->id;
          if($request['paidamount']!=0)  
          {
         $salesAccount= Salesorderaccount::create([
            'orderid' => $lastorderID,
            'systemid' => $request['systemid'],
            'entryid' => $request['entryid'],
            'paymentdate' => date('Y-m-d', strtotime($request['currentdate'])),
            'paymentmethod' => $request['paymentmethod'],
            'paidamount' => $request['paidamount'],
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

          if($request['comments'] != '')
          {
              $salesComment= Salesordercomment::create([
                'orderid' => $lastorderID,
                'systemid' => $request['systemid'],
                'entryid' => $request['entryid'],
                'comments' => $request['comments'],
                'companyid' => $request['companyid'],
                'branchid' => $request['branchid'],
             
             ]);
          }
         return $lastorderID;
        }
        else{
            $Ordercount = Salesorder::where('systemid', $request['systemid'])->whereDate('created_at',Carbon::today())->where('isactive', true)->count();
            Log::info( "Ordercount ===>" .$Ordercount);
            if($Ordercount < $userinfo->entrylimit){
                $salesOrder = Salesorder::create([
                    'ordernumber' => $ordernumber,
                    'customerid' => $request['customerid'],
                    'refso' =>$request['refso'],
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
                    'agentid' => $request['agentid'],
                    'deliveryarea' => $request['deliveryarea'],
                    'codcharge' => $request['codcharge'],
                    'shipping' => $request['shipping'],
                    'currentdate' => date('Y-m-d', strtotime($request['currentdate'])),
                    'deliverydate' => $deliverydate,
                    'duedeliverydate' =>$duedate,
                 
                 ]);
        
                 $lastorderID = $salesOrder->id;
                  if($request['paidamount']!=0)  
                  {
                 $salesAccount= Salesorderaccount::create([
                    'orderid' => $lastorderID,
                    'systemid' => $request['systemid'],
                    'entryid' => $request['entryid'],
                    'paymentdate' => date('Y-m-d', strtotime($request['currentdate'])),
                    'paymentmethod' => $request['paymentmethod'],
                    'paidamount' => $request['paidamount'],
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
        
                  if($request['comments'] != '')
                  {
                      $salesComment= Salesordercomment::create([
                        'orderid' => $lastorderID,
                        'systemid' => $request['systemid'],
                        'entryid' => $request['entryid'],
                        'comments' => $request['comments'],
                        'companyid' => $request['companyid'],
                        'branchid' => $request['branchid'],
                     
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
            $gid = Product::where('id', $r['pitem'])->first();
            $itemdata = Salesorderitem::create([
                'orderid' =>    $lastorderID,
                'systemid' =>   $r['systemid'],
                'entryid' =>   $r['entryid'],
                'pitem' =>      $r['pitem'],
                'detail' =>     $r['detail'],
                'gcode' =>      $gid->groupid,
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
                Salesvariation::create([
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

    
    public function createIncomingPayment(Request $request)
    {

       //$orders = $request->all();
      // Log::info($orders);
        
        $req = $request->ordersList;
        $accountinfo = $request['accountInfo'];
        Log::info( $accountinfo ); 

        foreach ($req as $r) {
            Log::info( $r);

        $orderdata = Salesorder::findOrFail($r['id']);
       
        $salesAccount= Salesorderaccount::create([
           'orderid' => $r['id'],
           'systemid' => $accountinfo['systemid'],
           'entryid' =>   $accountinfo['entryid'],
           'paymentdate' => date('Y-m-d', strtotime($accountinfo['paymentdate'])),
           'paymentmethod' => $accountinfo['paymentmethod'],
           'paidamount' => $r['payable'],
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
        $salesAccountsumamount=$orderdata->paidamount+$r['payable'];
        $dueamount=  $orderdata->totalamount-$salesAccountsumamount;                          

        $orderdata->where('id', $r['id'])->update(['paidamount' =>$salesAccountsumamount,'dueamount' =>$dueamount]);
        }
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
        $orderdata = Salesorder::with('customerdata')
                                ->with('agentdata')
                                ->with('refsorder')
                                ->with(array('companydata'=>function($query){
                                    $query->select('id','companyname');}))
                                ->where('systemid', $userinfo->systemid)
                                ->where('companyid', $userinfo->companyid)
                                ->where('isactive',true)
                                ->where('id', $id)
                                ->get();

         $itemdata = Salesorderitem::with('productCode')
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
         $acountsdata = Salesorderaccount::where('systemid', $userinfo->systemid)
                                ->where('orderid', $id)
                                ->get();
        $commentsdata = Salesordercomment::where('systemid', $userinfo->systemid)
        ->where('orderid', $id)
        ->where('isactive',true)
        ->get();

        $jsonData = array("order" => $orderdata, "items" => $itemdata, "accounts"=> $acountsdata, "comments"=> $commentsdata);
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
            'customerid' => 'required',
            'agentid' => 'required',
            //'deliverydate' => 'required',
        ]);
        $porder = Salesorder::findOrFail($id);
        Log::info( "deliverydate ===>" . $request['deliverydate']); 
      $pretotalamount=$porder->totalamount-$porder->shipping;
        $totalamount=$pretotalamount+$request['shipping'];
        $porder->where('id', $id)->update(['totalamount' =>$totalamount
        ]);

        if($request['deliverydate'] == '1970-1-1')
        {
            
            $request['deliverydate']=NULL;
        }
        else{
           
            $request['deliverydate']=date('Y-m-d', strtotime($request['deliverydate']));
        }
        if($request['duedeliverydate'] == '1970-1-1')
        {
             $request['duedeliverydate']=NULL;  
            
        }
        else{
           $request['duedeliverydate']=date('Y-m-d', strtotime($request['duedeliverydate']));
        }

        $porder->update($request->all());
        if($request['orderdelivered'] == 1)
        { 
        Salesorderitem::where('orderid', $id)->update(array('delivered' => true));
        }
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
        $porder = Salesorder::findOrFail($id);
        if($status === 'delivery')
        {
           // Log::info( "deliverydate ===>" . $porder->deliverydate); 
            if($porder->deliverydate == '')
            {
                $mytime = Carbon::now();
                $deliverydate=date('Y-m-d', strtotime($mytime));
                 // Log::info( "deliverydate ===>" . $deliverydate); 
                // Log::info( "deliverydate1 ===>" . $mytime); 
            }
            if($porder->duedeliverydate == '')
            {
                $mytime = Carbon::now();
                $duedeliverydate=date('Y-m-d', strtotime($mytime));
                 // Log::info( "deliverydate ===>" . $deliverydate); 
                // Log::info( "deliverydate1 ===>" . $mytime); 
            }
          
            $porder->where('id', $id)->update(['orderdelivered' => 1,'deliverydate' => $deliverydate,'duedeliverydate' =>$duedeliverydate]);
            Salesorderitem::where('orderid', $id)->update(array('delivered' => true));
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
    public function getrefso(Request $request,$id)
    {
        $systemid = base64_decode($request->header('sessionKey')); 
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
        return Salesorder::orderby('id', 'asc')->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Salesorder::orderby('id', 'asc')->where('customerid', $id)->where('systemid', $userinfo->systemid)->get(); 
        }
        if($userinfo->usertype == 'team'){
            return Salesorder::orderby('id', 'asc')->where('customerid', $id)->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->get(); 
        }
    }

    public function searchOrder(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        $search=$request['inputValue'];

        Log::info( "searchvalue ===>" . $search); 
      //  Log::info( "orderdelivered ===>" . $request['orderdelivered']); 
      if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        { 
        /*$data = Salesorder::with('customerdata');
        
        if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
            $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
            $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
           //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
           $data->whereBetween('deliverydate', [$datefrom, $dateto]);
        }
    


        //$data = Purchaseorder::with('vendordata')->$query
                        //->where('systemid', $request['systemid'])->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->get();
        $resp = $data->where('systemid', $userinfo->systemid)->where('customername', 'LIKE', "%{$request['inputValue']}%")->orderby('id', 'desc')->where('isactive',true)->get();*/

        $data = Salesorder::select('salesorders.*','customers.customername')
        ->join('customers', 'salesorders.customerid', '=', 'customers.id')
        ->where(function($q) use ($search) {
            $q->where('salesorders.ordernumber', 'like', '%' . $search . '%')
                ->orWhere('customers.customername', 'like', '%' . $search . '%')
                ->orWhere('customers.telephone', 'like', '%' . $search .'%') ;
        });
      
   
    
    if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
        $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
        $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
       //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
       $data->whereBetween('salesorders.deliverydate', [$datefrom, $dateto]);
    }
    
    if($request['orderdelivered'] != "" ){
            $data->where('salesorders.orderdelivered', $request['orderdelivered']);
    }

    $resp = $data->where('salesorders.systemid', $userinfo->systemid)
                 ->where('salesorders.companyid', $userinfo->companyid)
                 ->orderby('salesorders.id', 'desc')
                 ->where('salesorders.isactive',true)
                 ->paginate(20);
        return response()->json($resp);
     }
     if($userinfo->usertype == 'team')
        { 
            $data = Salesorder::select('salesorders.*','customers.customername')
            ->join('customers', 'salesorders.customerid', '=', 'customers.id')
            ->where(function($q) use ($search) {
                $q->where('salesorders.ordernumber', 'like', '%' . $search . '%')
                    ->orWhere('customers.customername', 'like', '%' . $search . '%')
                    ->orWhere('customers.telephone', 'like', '%' . $search .'%') ;
            });
        
        if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
            $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
            $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
           //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
           $data->whereBetween('salesorders.deliverydate', [$datefrom, $dateto]);
        }
    
        if($request['orderdelivered'] != "" ){
            $data->where('salesorders.orderdelivered', $request['orderdelivered']);
       }
    

        //$data = Purchaseorder::with('vendordata')->$query
                        //->where('systemid', $request['systemid'])->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->get();
        //$resp = $data->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('customerid', $request['customerid'])->orderby('id', 'desc')->where('isactive',true)->get();
        $resp = $data->where('salesorders.systemid', $userinfo->systemid)
                     ->where('salesorders.companyid', $userinfo->companyid)
                     ->orderby('salesorders.id', 'desc')
                     ->where('salesorders.isactive',true)
                     ->paginate(20);
        return response()->json($resp);
     }
    }
    public function searchIncomingPayment(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        $search=$request['inputValue'];

        Log::info( "searchvalue ===>" . $search); 
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        { 
        /*$data = Salesorder::with('customerdata');
        
        if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
            $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
            $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
           //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
           $data->whereBetween('deliverydate', [$datefrom, $dateto]);
        }
    


        //$data = Purchaseorder::with('vendordata')->$query
                        //->where('systemid', $request['systemid'])->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->get();
        $resp = $data->where('systemid', $userinfo->systemid)->where('customername', 'LIKE', "%{$request['inputValue']}%")->orderby('id', 'desc')->where('isactive',true)->get();*/

        $data = Salesorder::select('salesorders.*','customers.customername')
        ->join('customers', 'salesorders.customerid', '=', 'customers.id')
        ->where(function($q) use ($search) {
            $q->where('salesorders.ordernumber', 'like', '%' . $search . '%')
                ->orWhere('customers.customername', 'like', '%' . $search . '%')
                ->orWhere('customers.telephone', 'like', '%' . $search .'%') ;
        });
      
   
    
    if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
        $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
        $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
       //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
       $data->whereBetween('salesorders.deliverydate', [$datefrom, $dateto]);
    }



   
    $resp = $data->where('salesorders.systemid', $userinfo->systemid)
                 ->where('salesorders.companyid', $userinfo->companyid)
                 ->orderby('salesorders.id', 'desc')
                 ->where('salesorders.isactive',true)
                 ->get();
        return response()->json($resp);
     }
     if($userinfo->usertype == 'team')
        { 

            $data = Salesorder::select('salesorders.*','customers.customername')
            ->join('customers', 'salesorders.customerid', '=', 'customers.id')
            ->where(function($q) use ($search) {
                $q->where('salesorders.ordernumber', 'like', '%' . $search . '%')
                    ->orWhere('customers.customername', 'like', '%' . $search . '%')
                    ->orWhere('customers.telephone', 'like', '%' . $search .'%') ;
            });
          
        /*$data = Salesorder::with(array('customerdata'=>function($query){
            $query->where('customername', 'LIKE', "%$search%");}))*/
        
        if($request['deliverydate'] != "" && $request['duedeliverydate'] != ""){
            $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
            $dateto=date('Y-m-d', strtotime($request['duedeliverydate']));
           //$query= whereBetween('deliverydate', [$datefrom, $dateto]);
           $data->whereBetween('salesorders.deliverydate', [$datefrom, $dateto]);
        }
    


        //$data = Purchaseorder::with('vendordata')->$query
                        //->where('systemid', $request['systemid'])->where('vendorid', $request['vendorid'])->orderby('id', 'desc')->where('isactive',true)->get();
        //$resp = $data->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('customerid', $request['customerid'])->orderby('id', 'desc')->where('isactive',true)->get();
        $resp = $data->where('salesorders.systemid', $userinfo->systemid)
                     ->where('salesorders.companyid', $userinfo->companyid)
                     ->orderby('salesorders.id', 'desc')
                     ->where('salesorders.isactive',true)
                     ->get();
        return response()->json($resp);
     }
    }
}
