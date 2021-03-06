<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Sale;
use App\DetailSale;
use App\User;
use App\Client;
use App\Notifications\NotifyAdmin;

class SaleController extends Controller
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
            $sales = Sale::join('clients','sales.client_id','clients.id')
                        ->join('users', 'sales.user_id', 'users.id')
                        ->select('sales.*','clients.name', 'users.user_name') 
                        ->orderBy('sales.id', 'DESC')
                        ->paginate(8);
            }
        else
        {
            $sales = Sale::join('clients','sales.client_id','clients.id')
                        ->join('users', 'sales.user_id', 'users.id')
                        ->select('sales.*','clients.name', 'users.user_name')   
                        ->where('sales.' . $criteria, 'like', '%' . $search . '%')
                        ->orderBy('sales.id', 'DESC')
                        ->paginate(8);               
        }

        $counter = Sale::all()->count();
        $counter = $counter + 1;
        $num_sale = str_pad($counter,6,"0",STR_PAD_LEFT);    
        
        return [
            'pagination' => [
                'total'         =>  $sales->total(),
                'current_page'  =>  $sales->currentPage(), 
                'per_page'      =>  $sales->perPage(), 
                'last_page'     =>  $sales->lastPage(),
                'from'          =>  $sales->firstItem(),
                'to'            =>  $sales->lastItem(), 
            ],

        'sales' => $sales,
        'num_sale' => $num_sale
        ];
    }


    public function get_header(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;                  

        $sale = Sale::join('clients','sales.client_id','clients.id')
                    ->join('users', 'sales.user_id', 'users.id')
                    ->select('sales.*','clients.name', 'users.user_name')
                    ->where('sales.id', $id) 
                    ->orderBy('sales.id', 'DESC')
                    ->take(1)->get();


        return ['sale' => $sale];
    }

    public function get_detail(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;

        $datailsales = DetailSale::join('products','detail_sales.product_id','products.id')
                    ->select('detail_sales.*','products.name as product_name')
                    ->where('detail_sales.sale_id', $id) 
                    ->orderBy('detail_sales.id', 'DESC')
                    ->get();
        
        return ['datailsales' => $datailsales];
    }

    public function invoice_pdf(Request $request, $id)
    {
        $sale = Sale::join('clients', 'sales.client_id', 'clients.id')
                ->join('users', 'sales.user_id', 'users.id')
                ->select('sales.*', 'clients.name', 'clients.type_document', 'clients.num_document as num_document', 'clients.address', 'clients.email', 'clients.num_phone', 'users.user_name')
                ->where('sales.id', $id)
                ->orderBy('sales.id', 'DESC')
                ->take(1)->get();       

        $detail_sale = DetailSale::join('products', 'detail_sales.product_id', 'products.id')
                ->SELECT('detail_sales.*', 'products.name as product_name', 'products.description')
                ->where('detail_sales.sale_id', $id)
                ->orderBy('detail_sales.id', 'DESC')
                ->get();

        $num_document = Sale::join('clients', 'sales.client_id', 'clients.id')
                        ->select('clients.num_document')
                        ->where('sales.id', $id)
                        ->take(1)
                        ->get();

        // dd($num_document);                
        //Vista del reporte PDF en el explorador 
        $num_sale = Sale::select('serie_voucher')->where('id', $id)->get();        
        $view = \View::make('pdf.salespdf', compact('sale', 'detail_sale', 'num_document'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('venta - '.$num_sale[0]->num_voucher.'.pdf');

        //Reporte de PDF download -------------------------------------------           
        // $num_sale = Sale::select('num_voucher')->where('id', $id)->get();
        // $pdf = \PDF::loadView('pdf.salespdf', compact('sale', 'detail_sale', 'num_document'));
        // return $pdf->download('venta - '.$num_sale[0]->num_voucher.'.pdf');               
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
            $sale = new Sale();
            $sale->client_id = $request->client_id;
            $sale->user_id = \Auth::user()->id;
            $sale->type_voucher = $request->type_voucher;
            $sale->serie_voucher = $request->serie_voucher;
            $sale->num_voucher = $request->num_voucher;
            $sale->date_hour = $mytime->toDateString();
            $sale->tax = $request->tax;
            $sale->total = $request->total;
            $sale->state = 'Registrado';
            $sale->save();

            //Detalle del Ingresdo

            $detail_sales = $request->data;
       

            //Recoremos los elementos
            foreach ($detail_sales as $key => $detail) {
                $detail_sales = new DetailSale();
                $detail_sales->sale_id = $sale->id;
                $detail_sales->product_id = $detail['product_id'];
                $detail_sales->quantity = $detail['quantity'];
                $detail_sales->price = $detail['price'];
                $detail_sales->discount  = $detail['price'];
                $detail_sales->save();
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

            return [
                'id' => $sale->id
            ];

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

        $sale = Sale::findOrFail($request->id);
        $sale->state = 'Anulado';
        $sale->save();

    }
}
