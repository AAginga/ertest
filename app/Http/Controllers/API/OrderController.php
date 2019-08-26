<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderDetails;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::latest()->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'order_number' => 'required|string|max:191',
          ]);
  
        $order = Order::create([
            'order_number' =>$request['order_number'],
        ]);

        $this->updateOrderproducts($request, $order->id);  
        

        return['message', 'success'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $order = Order::findOrFail($id);

        $this->validate($request,[
            'order_number' => 'required|string|max:191',
          ]);

        $order->update($request->all());
        
        OrderDetails::where('order_id', $id)->delete();
        
        $this->updateOrderproducts($request, $id);  

        return['message','Order Updated'];
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

    protected function updateOrderproducts(Request $request, $id)
    {
        if ($request->checkedproducts) {
            foreach ($request->checkedproducts as $product) {
                OrderDetails::create([
                    'order_id' => $id,
                    'product_id' => $product,
                ]);
            }
        }
    }
}
