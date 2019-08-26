<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Supplier;
use App\SupplierProduct;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

     //   $allSuppliers = Supplier::all();

    //    $product = Product::find($id);
    //    $categoriesForProduct = $product->categories()->get();
        return Product::latest()->paginate(5);
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
            'name' => 'required|string|max:191',
            'quantity' => 'required|string|max:45',
            'description' =>'required|string|max:255'
          ]);
  
        $product = Product::create([
              'name' =>$request['name'],
              'quantity' =>$request['quantity'],
              'description' =>$request['description'],
        ]);

        $this->updateProductSuppliers($request, $product->id);  
        

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

        $product = Product::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'quantity' => 'required|string|max:45',
            'description' =>'required|string|max:255'
          ]);

        $product->update($request->all());
        
        SupplierProduct::where('product_id', $id)->delete();
        
        $this->updateProductSuppliers($request, $id);  

        return['message','Product Updated'];
       
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


    protected function updateProductSuppliers(Request $request, $id)
    {
        if ($request->checkedSuppliers) {
            foreach ($request->checkedSuppliers as $supplier) {
                SupplierProduct::create([
                    'product_id' => $id,
                    'supply_id' => $supplier,
                ]);
            }
        }
    }
}
