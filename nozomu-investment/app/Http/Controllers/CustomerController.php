<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class CustomerController extends Controller
{

    public $nab = 3;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function showAll() {
        return response()->json(Customer::all());
    }

    public function showById($id) {
        return response()->json(Customer::find($id));
    }

    public function roundDown($val, $behindComma) {
        return floor($val * pow(10, $behindComma)) / pow(10,$behindComma);
    }
    
    public function addCustomer(Request $request) {
        $name = $request->input('name');
        $username = $request->input('username');
        $hashed_pass = Hash::make($request->input('password'));
        // rounding down
        $raw_balance = $request->input('initBalance');
        $balance = $this->roundDown($raw_balance, 2);
        $raw_unit = $balance/($this->nab);
        $unit = $this->roundDown($raw_unit, 4);

        $res = Customer::create([
            'name' => $name,
            'username' => $username,
            'password' => $hashed_pass,
            'balance' => $balance,
            'unit' => $unit
        ]);

        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Customer succesfully added!',
                'data' => $res
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Customer can\'t added!',
                'data' => ''
            ], 400);
        }

    }

    //
}
