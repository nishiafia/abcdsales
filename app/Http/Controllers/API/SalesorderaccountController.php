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
use Illuminate\Support\Facades\Log;

class SalesorderaccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'paymentmethod' => 'required',
            'paidamount' => 'required',
            'paymentdate' => 'required',
        ]);
        $orderid=$request['orderid'];
        $orderdata = Salesorder::findOrFail($orderid);
       
         $salesAccount= Salesorderaccount::create([
            'orderid' => $request['orderid'],
            'systemid' => $request['systemid'],
            'entryid' =>   $request['entryid'],
            'paymentdate' => date('Y-m-d', strtotime($request['paymentdate'])),
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
         $salesAccountsumamount=Salesorderaccount::where('orderid', $orderid)
                                    ->sum('paidamount');
          $dueamount=  $orderdata->totalamount-$salesAccountsumamount;                         

        $orderdata->where('id', $orderid)->update(['paidamount' =>$salesAccountsumamount,'dueamount' =>$dueamount]);

       // Log::info( "sumamount ===>" . $purchaseAccountsumamount,$dueamount,$orderdata->totalamount); 
        return response()->json([
            'message' => 'Payment successfully'
           ]);
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
        $data = Salesorderaccount::where('systemid', $userinfo->systemid)
                        ->where('orderid', $id)
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
            'paymentmethod' => 'required',
            'paidamount' => 'required',
            'paymentdate' => 'required',
        ]);
        $orderid=$request['orderid'];
        $orderdata = Salesorder::findOrFail($orderid);
        $accountdata = Salesorderaccount::findOrFail($id);

        $prepaidamount=$orderdata->paidamount-$accountdata->paidamount;
        $predueamount=$orderdata->dueamount+$accountdata->paidamount;

        $accountdata->update($request->all());

        $paidamount=$prepaidamount+$request['paidamount'];
        $dueamount=$predueamount-$request['paidamount'];

        $orderdata->where('id', $orderid)->update(['paidamount' =>$paidamount,
                                                    'dueamount' =>$dueamount
                                                    ]);


       // Log::info( "totalamount ===>" . $totalamount); 
        return response()->json([
        'message' => 'Payment Updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $accountdata = Salesorderaccount::findOrFail($id);
        $orderdata = Salesorder::findOrFail($accountdata->orderid);

       // $dueamount=$orderdata->dueamount-$accountdata->payamount;
        $paidamount=$orderdata->paidamount-$accountdata->paidamount;
        $dueamount=$orderdata->dueamount+$accountdata->paidamount;

        $orderdata->where('id', $accountdata->orderid)->update(['paidamount' =>$paidamount,
                                                                    'dueamount' =>$dueamount
                                                                    ]);
        
        $accountdata->delete();
        return response()->json([
         'message' => 'Payment deleted successfully'
        ]);
    }
    public function getpaymentmethodinformation(Request $request, $id)
    {
        $systemid = base64_decode($request->header('sessionKey'));  
        $userinfo = User::where('id', $systemid)->first();  
        $data = Salesorderaccount::with('getbankname')
                        ->where('systemid', $userinfo->systemid)
                        ->where('id', $id)
                        ->get();

    	return response()->json($data);
    }
}
