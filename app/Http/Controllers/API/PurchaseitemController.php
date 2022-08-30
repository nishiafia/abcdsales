<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Purchaseitem;
use App\Purchaseorder;
use App\Product;
use App\Variation;
use App\User;
use App\Purchasevariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseitemController extends Controller
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
            'pitem' => 'required',
            'qty' => 'required',
            'price' => 'required',
        ]);
        $orderid=$request['orderid'];
        $orderdata = Purchaseorder::findOrFail($orderid);

        $gid = Product::where('id', $request['pitem'])->first();
       // $itemexists = Purchaseitem::where('pitem', $request['pitem'])->where('orderid', $orderid)->where('isactive',true)->where('returnitem',0)->first();
      // if(!$itemexists)
      // {
        $itemdata= Purchaseitem::create([
            'orderid' =>    $orderid,
            'systemid' =>   $request['systemid'],
            'entryid' =>   $request['entryid'],
            'pitem' =>      $request['pitem'],
            'gcode' =>      $gid->groupid,
            'detail' =>     $request['detail'],
            'qty' =>        $request['qty'],
            'price' =>      $request['price'],
            'excat' =>      $request['excat'],
            'discountid'  =>  $request['discountid'],
            'taxid' =>        $request['taxid'],
            'total' =>      $request['total'],
            'discountfigure' => $request['discountfigure'],
            'taxfigure' =>      $request['taxfigure'],
            'delivered' =>  $request['delivered'],
        ]);
         $totalamount=$orderdata->totalamount+$request['total'];
         $totaltax=$orderdata->totaltax+$request['taxfigure'];
         $totaldiscount=$orderdata->totaldiscount+$request['discountfigure'];
        // $shipping=$orderdata->shipping+$request['shipping'];
         $dueamount=$orderdata->dueamount+$request['total'];

        $orderdata->where('id', $orderid)->update(['totalamount' =>$totalamount,
                                                    'totaltax' =>$totaltax,
                                                    'totaldiscount' =>$totaldiscount,
                                                    //'shipping' =>$shipping,
                                                    'dueamount' =>$dueamount
                                                    ]);


        Log::info( "totalamount ===>" . $totalamount);

        $lastitemID = $itemdata->id;
            foreach ($request['variationsid'] as $r1) {
                $labelid = Variation::where('id', $r1)->first();
                Purchasevariation::create([
                    'orderid' =>    $orderid,
                    'peritemid' =>  $lastitemID,
                    'itemid' =>      $request['pitem'],
                    'vlabelid' =>     $labelid->vlabelid,
                    'variationid' =>     $r1,
                ]);

            }
        return response()->json([
            'message' => 'Item Added successfully'
           ]);
        /*}
        else{
            return response()->json('Exist');
        }*/
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
        $data = Purchaseitem::with('productCode')
                       // ->with('pcolor')
                       // ->with('psize')
                        ->with('excategory')
                        ->where('systemid', $userinfo->systemid)
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
            'pitem' => 'required',
            'qty' => 'required',
            'price' => 'required',
        ]);

        $orderid=$request['orderid'];
        $orderdata = Purchaseorder::findOrFail($orderid);
        $itemdata = Purchaseitem::findOrFail($id);

        $pretotalamount=$orderdata->totalamount-$itemdata->total;
        $pretotaltax=$orderdata->totaltax-$itemdata->taxfigure;
        $pretotaldiscount=$orderdata->totaldiscount-$itemdata->discountfigure;
        //$shipping=$orderdata->shipping+$request['shipping'];
        $predueamount=$orderdata->dueamount-$itemdata->total;
        $gid = Product::where('id', $request['pitem'])->first();
        // $itemexists = Purchaseitem::where('pitem', $request['pitem'])->where('orderid', $orderid)->where('isactive',true)->where('returnitem',0)->first();
       // if(!$itemexists)
       // {

         $itemdata->where('id', $id)->update([
         'orderid' =>    $orderid,
         'systemid' =>   $request['systemid'],
         'entryid' =>   $request['entryid'],
         'pitem' =>      $request['pitem'],
         'gcode' =>      $gid->groupid,
         'detail' =>     $request['detail'],
         'qty' =>        $request['qty'],
         'price' =>      $request['price'],
         'excat' =>      $request['excat'],
         'discountid'  =>  $request['discountid'],
         'taxid' =>        $request['taxid'],
         'total' =>      $request['total'],
         'discountfigure' => $request['discountfigure'],
         'taxfigure' =>      $request['taxfigure'],
         'delivered' =>  $request['delivered'],
         ]);

        $totalamount=$pretotalamount+$request['total'];
        $totaltax=$pretotaltax+$request['taxfigure'];
        $totaldiscount=$pretotaldiscount+$request['discountfigure'];
       // $shipping=$orderdata->shipping+$request['shipping'];
        $dueamount=$predueamount+$request['total'];

       $orderdata->where('id', $orderid)->update(['totalamount' =>$totalamount,
                                                   'totaltax' =>$totaltax,
                                                   'totaldiscount' =>$totaldiscount,
                                                   'dueamount' =>$dueamount
                                                   ]);


       Log::info( "totalamount ===>" . $totalamount);
       Purchasevariation::where('peritemid', $id)->delete();


       foreach ($request['variationsid'] as $r1) {
        $labelid = Variation::where('id', $r1)->first();
        Purchasevariation::create([
            'orderid' =>    $orderid,
            'peritemid' =>  $id,
            'itemid' =>      $request['pitem'],
            'vlabelid' =>     $labelid->vlabelid,
            'variationid' =>     $r1,
        ]);

    }

       return response()->json([
           'message' => 'Item Updated successfully'
          ]);
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
        $itemdata = Purchaseitem::findOrFail($id);
        $orderdata = Purchaseorder::findOrFail($itemdata->orderid);
        if($status === 'cancel'){


        $pretotalamount=$orderdata->totalamount-$itemdata->total;
        $pretotaltax=$orderdata->totaltax-$itemdata->taxfigure;
        $pretotaldiscount=$orderdata->totaldiscount-$itemdata->discountfigure;
        //$shipping=$orderdata->shipping+$request['shipping'];
        $predueamount=$orderdata->dueamount-$itemdata->total;

        $orderdata->where('id', $itemdata->orderid)->update(['totalamount' =>$pretotalamount,
                                                            'totaltax' =>$pretotaltax,
                                                            'totaldiscount' =>$pretotaldiscount,
                                                            'dueamount' =>$predueamount
                                                            ]);

        $itemdata->where('id', $id)->update(['isactive' => false]);
        return response()->json([
         'message' => 'Item Cancel successfully'
        ]);
        }
        if($status === 'return'){


            $pretotalamount=$orderdata->totalamount-$itemdata->total;
            $pretotaltax=$orderdata->totaltax-$itemdata->taxfigure;
            $pretotaldiscount=$orderdata->totaldiscount-$itemdata->discountfigure;
            //$shipping=$orderdata->shipping+$request['shipping'];
            $predueamount=$orderdata->dueamount-$itemdata->total;
            $orderdata->where('id', $itemdata->orderid)->update(['totalamount' =>$pretotalamount,
                                                                'totaltax' =>$pretotaltax,
                                                                'totaldiscount' =>$pretotaldiscount,
                                                                'dueamount' =>$predueamount
                                                                ]);
            $itemdata->where('id', $id)->update(['returnitem' => 1]);
            return response()->json([
             'message' => 'Item Return successfully'
            ]);
            }
    }
    public function updateItemDelivery(Request $request)
    {
        $req = $request->itemids;
        $orderid=$request->porderid;
       // Log::info( $req);
        foreach ($req as $r) {
            Log::info( $r);
            Purchaseitem::where('id', $r)->update(array('delivered' => true));
        }

        $porder = Purchaseorder::findOrFail($orderid);
        $porder->where('id', $orderid)->update(['orderdelivered' => 4]);
        return response()->json([
            'message' => 'Item Delivery successfully'
           ]);
    }
}
