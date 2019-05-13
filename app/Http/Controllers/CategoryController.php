<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
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
            $categories = Category::orderBy('id', 'DESC')->paginate(3);
        }
        else
        {
            $categories = Category::where($criteria, 'like', '%' . $search . '%')
                        ->orderBy('id', 'DESC')
                        ->paginate(8);               
        }    

        
        return [
            'pagination' => [
                'total'         =>  $categories->total(),
                'current_page'  =>  $categories->currentPage(), 
                'per_page'      =>  $categories->perPage(), 
                'last_page'     =>  $categories->lastPage(),
                'from'          =>  $categories->firstItem(),
                'to'            =>  $categories->lastItem(), 
            ],

        'categories' => $categories
        ];
    }

    public function select_categories(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $categories = Category::where('condition', 1)
                        ->select('id', 'name')
                        ->orderBy('name', 'ASC')
                        ->get();

        return ['categories' => $categories ];                
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
        // $category = new Category();
        // $category->name = $request->name;
        // $category->description = $request->description;
        // $category->condition = '1';
        // $category->save();

        $category = Category::create($request->all());
        $category->save();

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

        $category = Category::findOrFail($request->id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->condition = '1';
        $category->save();
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

        $category = Category::findOrFail($request->id);
        $category->condition = '0';
        $category->save();
    }

    public function enable(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $category = Category::findOrFail($request->id);
        $category->condition = '1';
        $category->save();
    }
}
