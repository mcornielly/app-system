<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search = $request->search;
        $criteria = $request->criteria;

        if($search==''){
            $products = Product::join('categories','products.category_id','categories.id')
                ->select('products.*','categories.name as category_name')
                ->orderBy('products.id', 'DESC')->paginate(3);
        }
        else
        {

            $products = Product::join('categories','products.category_id','categories.id')
                ->select('products.*','categories.name as category_name')
                ->where('products.'.$criteria, 'like', '%' . $search . '%')
                ->orderBy('products.id', 'DESC')->paginate(3);              
        }    

        
        return [
            'pagination' => [
                'total'         =>  $products->total(),
                'current_page'  =>  $products->currentPage(), 
                'per_page'      =>  $products->perPage(), 
                'last_page'     =>  $products->lastPage(),
                'from'          =>  $products->firstItem(),
                'to'            =>  $products->lastItem(), 
            ],

        'products' => $products
        ];
    }

    public function list_product(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search = $request->search;
        $criteria = $request->criteria;

        if($search==''){
            $products = Product::join('categories','products.category_id','categories.id')
                ->select('products.*','categories.name as category_name')
                ->orderBy('products.id', 'DESC')->paginate(10);
        }
        else
        {

            $products = Product::join('categories','products.category_id','categories.id')
                ->select('products.*','categories.name as category_name')
                ->where('products.'.$criteria, 'like', '%' . $search . '%')
                ->orderBy('products.id', 'DESC')->paginate(10);              
        }    

        
        return ['products' => $products];
    }


    public function search_product(Request $request)
    {
        if(!$request->ajax()) return('/');

        $filter = $request->filter;
        $product = Product::where('code', $filter)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->take(1)->get();
 
            return ['product' => $product];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        if(!$request->ajax()) return redirect('/');

        // $rules = [
        //     'name' => 'required|string|max:255',
        // ];
        // $messages = [
        //     'name.required' => 'Es necesario ingresar el nombre del usuario.',
        // ];
        // $this->validate($request, $rules, $messages);
        //----------------------------------------------------------------------
        
        //Todo el metodo de registro por separado.
        //----------------------------------------
        // $product = new Producto();
        // $product->category_id = $request->category_id;
        // $product->code = $request->code;
        // $product->name = $request->name;
        // $product->price = $request->price;
        // $product->stock = $request->stock;
        // $product->description = $request->description;
        // $product->condition = '1';
        // $product->save();

        $product = Product::create($request->all());
        $product->save();

        // return back()->with('info','Usuario Actulizado...!');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $product = Product::findOrFail($request->id);
        $product->category_id = $request->category_id;
        $product->code = $request->code;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->description = $request->description;
        $product->condition = '1';
        $product->save();
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

    public function disable(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $product = Product::findOrFail($request->id);
        $product->condition = '0';
        $product->save();
    }

    public function enable(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $product = Product::findOrFail($request->id);
        $product->condition = '1';
        $product->save();
    }
}
