<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;

class ClientController extends Controller
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
            $clients = Client::orderBy('id', 'DESC')->paginate(3);
        }
        else
        {
            $clients = Client::where($criteria, 'like', '%' . $search . '%')
                        ->orderBy('id', 'DESC')
                        ->paginate(3);               
        }    

        
        return [
            'pagination' => [
                'total'         =>  $clients->total(),
                'current_page'  =>  $clients->currentPage(), 
                'per_page'      =>  $clients->perPage(), 
                'last_page'     =>  $clients->lastPage(),
                'from'          =>  $clients->firstItem(),
                'to'            =>  $clients->lastItem(), 
            ],

        'clients' => $clients
        ];
    }

    public function select_client(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        $filter = $request->filter;

        $clients = Client::where('name', 'like', '%' . $filter . '%')
            ->orWhere('num_document', 'like', '%' . $filter . '%')
            ->select('clients.*')
            ->orderBy('name', 'ASC')
            ->get();

            return ['clients' => $clients];

            dd($clients); 
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
        
        //Todo el metodo de registro por separado.
        //----------------------------------------
        // $client = new Client();
        // $client->name = $request->name;
        // $client->type_client = $request->type_client;
        // $client->num_document = $request->num_document;
        // $client->address = $request->address;
        // $client->num_phone = $request->num_phone;
        // $client->email = $request->email;
        // $client->save();

        $client = Client::create($request->all());
        $client->save();

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

        $client = Client::findOrFail($request->id);
        $client->name = $request->name;
        $client->type_document = $request->type_document;
        $client->num_document = $request->num_document;
        $client->address = $request->address;
        $client->num_phone = $request->num_phone;
        $client->email = $request->email;
        $client->save();
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
