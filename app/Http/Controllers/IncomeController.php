<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Income;
use App\DetailIncome;
use App\User;
use App\Notifications\NotifyAdmin;

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
                        ->paginate(8);
            }
        else
        {
            $incomens = Incomen::join('clients','incomes.provider_id','clients.id')
                        ->join('users', 'incomes.user_id', 'users.id')
                        ->select('incomes.*','clients.name', 'users.user_name')  
                        ->where($criteria, 'like', '%' . $search . '%')
                        ->orderBy('id', 'DESC')
                        ->paginate(8);               
        }

        $counter = Income::all()->count();
        $counter = $counter + 1;
        $num_invoice = str_pad($counter,6,"0",STR_PAD_LEFT);    

        
        return [
            'pagination' => [
                'total'         =>  $incomes->total(),
                'current_page'  =>  $incomes->currentPage(), 
                'per_page'      =>  $incomes->perPage(), 
                'last_page'     =>  $incomes->lastPage(),
                'from'          =>  $incomes->firstItem(),
                'to'            =>  $incomes->lastItem(), 
            ],

        'incomes' => $incomes,
        'num_invoice' => $num_invoice
        ];
    }

    public function get_header(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;


        $incomes = Income::join('clients','incomes.provider_id','clients.id')
                    ->join('users', 'incomes.user_id', 'users.id')
                    ->select('incomes.*','clients.name', 'users.user_name')
                    ->where('incomes.id', $id) 
                    ->orderBy('incomes.id', 'DESC')
                    ->take(1)->get();       

        return ['incomes' => $incomes];


    }

    public function get_detail(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;

        $detailincomes = DetailIncome::join('products','detail_incomes.product_id','products.id')
                    ->select('detail_incomes.*','products.name as product_name')
                    ->where('detail_incomes.income_id', $id) 
                    ->orderBy('detail_incomes.id', 'DESC')
                    ->take(1)->get();
        
        return ['detailincomes' => $detailincomes];
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
            $income->user_id = \Auth::user()->id;
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
            foreach ($detail_incomes as $key => $detail) {
                $detail_income = new DetailIncome();
                $detail_income->income_id = $income->id;
                $detail_income->product_id = $detail['product_id'];
                $detail_income->quantity = $detail['quantity'];
                $detail_income->price = $detail['price'];
                $detail_income->save();
            }

            $dateNow = date('Y-m-d');
            $numSales = DB::table('sales')->whereDate('created_at', $dateNow)->count();
            $numIncomes = DB::table('incomes')->whereDate('created_at', $dateNow)->count();

            $arrayData = [
                'incomes' => [
                    'num' => $numIncomes,
                    'msj' => 'Ingresos'
                ],
                'sales'   => [
                    'num' => $numSales,
                    'msj' => 'Ventas'
                ]       
            ];

            $allUser = User::all();

            foreach($allUser as $notification){
                User::findOrFail($notification->id)->notify(new NotifyAdmin($arrayData));
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

    public function disable(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $income = Income::findOrFail($request->id);
        $income->state = 'Anulado';
        $income->save();

    }
}
