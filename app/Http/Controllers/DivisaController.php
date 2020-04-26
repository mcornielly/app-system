<?php

namespace App\Http\Controllers;

use App\Divisa;
use Illuminate\Http\Request;

class DivisaController extends Controller
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
            $divisa = Divisa::orderBy('id', 'DESC')->paginate();
        }
        else
        {
            $divisa = Divisa::where($criteria, 'like', '%' . $search . '%')
                        ->orderBy('id', 'DESC')
                        ->paginate(8);               
        }    
        
        return [
            'pagination' => [
                'total'         =>  $divisa->total(),
                'current_page'  =>  $divisa->currentPage(), 
                'per_page'      =>  $divisa->perPage(), 
                'last_page'     =>  $divisa->lastPage(),
                'from'          =>  $divisa->firstItem(),
                'to'            =>  $divisa->lastItem(), 
            ],

        'divisa' => $divisa
        ];
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
        //
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
    public function update(Request $request, $id)
    {
        //
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
}
