<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Client;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        $search = $request->search;
        $criteria = $request->criteria;

        if($search==''){
            $clients = User::join('clients','users.id','clients.id')
                        ->join('roles', 'users.role_id', 'roles.id')
                        ->select('clients.*','users.*','roles.name as role_name', 'roles.description') 
                        ->orderBy('clients.id', 'DESC')
                        ->paginate(3);
            }
        else
        {
            $clients = User::join('clients','users.id','clients.id')
                        ->join('roles', 'users.role_id', 'roles.id')
                        ->select('clients.*','users.*','roles.name as role_name', 'roles.description') 
                        ->where($criteria, 'like', '%' . $search . '%')
                        ->orderBy('clients.id', 'DESC')
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
        //Se registra primero el cliente
        //----------------------------------------
        // $client = new Client();
        // $client->name = $request->name;
        // $client->type_client = $request->type_client;
        // $client->num_document = $request->num_document;
        // $client->address = $request->address;
        // $client->num_phone = $request->num_phone;
        // $client->email = $request->email;
        // $client->save();

        //Se guarda el proveedor
        //-----------------------------------------
        // $provider = new Provider();
        // $provider->contact_name     = $request->contact_name;
        // $provider->contact_phone    = $request->contact_phone;
        // $provider->id               = $client->id;
        // $provider->save();


        try{
            //Para el uso de todos sus clases se debe importar
            //Illuminate\Support\Facades\DB;
            DB::beginTransaction();

            $client = new Client();
            $client->name = $request->name;
            $client->type_document = $request->type_document;
            $client->num_document = $request->num_document;
            $client->address = $request->address;
            $client->num_phone = $request->num_phone;
            $client->email = $request->email;
            $client->save();

            //Se guarda el Usuario
            //-----------------------------------------
            $user = new User();
            $user->id               = $client->id;
            $user->role_id          = $request->role_id;
            $user->user_name        = $request->user_name;
            $user->password         = bcrypt($request->password);
            $user->condition        = 1; 
            $user->save();

            // $client = Client::create($request->all());
            // $client->save();

            // $provider = Provider::create($request->all());
            // $provider->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
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


        try{
            //Para el uso de todos sus clases se debe importar
            //Illuminate\Support\Facades\DB;
            DB::beginTransaction();

            //Se busca primero el ID del proveedor para modificar.
            $user = User::findOrFail($request->id);
            $client = Client::findOrFail($user->id);

            $client->name = $request->name;
            $client->type_document = $request->type_document;
            $client->num_document = $request->num_document;
            $client->address = $request->address;
            $client->num_phone = $request->num_phone;
            $client->email = $request->email;
            $client->save();

            $user->role_id          = $request->role_id;
            $user->user_name        = $request->user_name;
            $user->password         = bcrypt($request->password);
            $user->condition        = 1; 
            $user->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
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

        $user = User::findOrFail($request->id);
        $user->condition = '0';
        $user->save();
    }

    public function enable(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $user = User::findOrFail($request->id);
        $user->condition = '1';
        $user->save();
    }
}
