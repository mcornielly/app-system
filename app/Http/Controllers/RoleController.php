<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search = $request->search;
        $criteria = $request->criteria;

        if($search==''){
            $roles = Role::orderBy('id', 'DESC')->paginate(3);
        }
        else
        {
            $roles = Role::where($criteria, 'like', '%' . $search . '%')
                        ->orderBy('id', 'DESC')
                        ->paginate(8);               
        }    

        
        return [
            'pagination' => [
                'total'         =>  $roles->total(),
                'current_page'  =>  $roles->currentPage(), 
                'per_page'      =>  $roles->perPage(), 
                'last_page'     =>  $roles->lastPage(),
                'from'          =>  $roles->firstItem(),
                'to'            =>  $roles->lastItem(), 
            ],

        'roles' => $roles
        ];
    }

    public function select_role()
    {
        $roles = Role::where('condition', '1')
                ->select('id', 'name')
                ->orderBy('name', 'ASC')
                ->get();

        return ['roles' => $roles];        
    }
}
