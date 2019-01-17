<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Income;
use App\DetailIncome;

class IncomeController extends Controller
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
            $incomes = Income::join('clients','incomes.provider_id','clients.id')
                        ->join('users', 'incomes.user_id', 'users.id')
                        ->select('incomes.*','clients.name', 'users.user_name') 
                        ->orderBy('id', 'DESC')
                        ->paginate(3);
            }
        else
        {
            $incomens = Incomen::join('clients','incomes.provider_id','clients.id')
                        ->join('users', 'incomes.user_id', 'users.id')
                        ->select('incomes.*','clients.name', 'users.user_name')  
                        ->where($criteria, 'like', '%' . $search . '%')
                        ->orderBy('id', 'DESC')
                        ->paginate(3);               
        }    

        
        return [
            'pagination' => [
                'total'         =>  $incomes->total(),
                'current_page'  =>  $incomes->currentPage(), 
                'per_page'      =>  $incomes->perPage(), 
                'last_page'     =>  $incomes->lastPage(),
                'from'          =>  $incomes->firstItem(),
                'to'            =>  $incomes->lastItem(), 
            ],

        'incomes' => $incomes
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

        $mytime = Carbon::now('America/Caracas');


        try{
            //Para el uso de todos sus clases se debe importar
            //Illuminate\Support\Facades\DB;
            DB::beginTransaction();

            //Se registra los ingresos
            $income = new Income();
            $income->provider_id = $request->provider_id;
            $income->user_id = \Auth::user()->id();
            $income->type_voucher = $request->type_voucher;
            $income->serie_voucher = $request->serie_voucher;
            $income->num_voucher = $request->num_voucher;
            $income->date_hour = $mytime->toDateString();
            $income->tax = $request->tax;
            $income->total = $request->total;
            $income->state = 'Registrado';
            $income->save();

            //Detalle del Ingresdo

            $detail_incomes = $request->data;        

            //Recoremos los elementos
            foreach ($detail_incomes as $key => $detail_income) {
                $detail_income = new DetailIncome();
                $detail_income = $income->id;
                $detail_income->product_id = $detail_income['product_id'];
                $detail_income->quantity = $detail_income['quantity'];
                $detail_income->price = $detail_income['price'];
                $detail_income->save();
            }

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

    public function enable(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $income = Income::findOrFail($request->id);
        $income->state = 'Anulado';
        $income->save();
    }
}