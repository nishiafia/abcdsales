<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Purchaseaccount;
use App\Purchaseorder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseaccountController extends Controller
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
            'payamount' => 'required',
            'paymentdate' => 'required',
        ]);
        $orderid=$request['orderid'];
        $orderdata = Purchaseorder::findOrFail($orderid);
         $purchaseAccount= Purchaseaccount::create([
            'orderid' => $request['orderid'],
            'systemid' => $request['systemid'],
            'entryid' =>   $request['entryid'],
            'paymentdate' => date('Y-m-d', strtotime($request['paymentdate'])),
            'paymentmethod' => $request['paymentmethod'],
            'payamount' => $request['payamount'],
            'dialcode' => $request['dialcode'],
            'bkashnumber' => $request['bkashnumber'],
            'bkashpin' => $request['bkashpin'],
            'cashpay' => $request['cashpay'],
            'bankid' => $request['bankid'],
            'accountholder' => $request['accountholder'],
            'accountnumber' => $request['accountnumber'],
            'branchname' => $request['branchname'],
         ]);
         $purchaseAccountsumamount=Purchaseaccount::where('orderid', $orderid)
                                    ->sum('payamount');
          $dueamount=  $orderdata->totalamount-$purchaseAccountsumamount;

        $orderdata->where('id', $orderid)->update(['paidamount' =>$purchaseAccountsumamount,'dueamount' =>$dueamount]);

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
        $data = Purchaseaccount::where('systemid', $userinfo->systemid)
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
            'payamount' => 'required',
            'paymentdate' => 'required',
        ]);
        $orderid=$request['orderid'];
        $orderdata = Purchaseorder::findOrFail($orderid);
        $accountdata = Purchaseaccount::findOrFail($id);

        $prepaidamount=$orderdata->paidamount-$accountdata->payamount;
        $predueamount=$orderdata->dueamount+$accountdata->payamount;

        $accountdata->update($request->all());

        $paidamount=$prepaidamount+$request['payamount'];
        $dueamount=$predueamount-$request['payamount'];

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
        $accountdata = Purchaseaccount::findOrFail($id);
        $orderdata = Purchaseorder::findOrFail($accountdata->orderid);

       // $dueamount=$orderdata->dueamount-$accountdata->payamount;
        $paidamount=$orderdata->paidamount-$accountdata->payamount;
        $dueamount=$orderdata->dueamount+$accountdata->payamount;

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
        $data = Purchaseaccount::with('getbankname')
                        ->where('systemid', $userinfo->systemid)
                        ->where('id', $id)
                        ->get();

    	return response()->json($data);
    }
}
