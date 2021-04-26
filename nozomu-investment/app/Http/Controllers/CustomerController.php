<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class CustomerController extends Controller
{

    public $nab = 3;
    public const UNIT_BEHIND_COMMA = 4;
    public const BALANCE_BEHIND_COMMA = 2;
    public const NAB_BEHIND_COMMA = 2;
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
        $balance = $this->roundDown($raw_balance, self::BALANCE_BEHIND_COMMA);
        $raw_unit = $balance/($this->nab);
        $unit = $this->roundDown($raw_unit, self::UNIT_BEHIND_COMMA);

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

    public function updateName($id, Request $request) {
        $oldData = Customer::find($id);
        $oldData->name = $request->input('newName');
        $res = $oldData->save();
        $newData = Customer::find($id);
        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Name updated',
                'data' => $newData
            ], 200);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Name not updated',
                'data' => ''
            ], 400);
        }
    }

    public function changeUnit($oldBalance) {
        $res = $oldBalance/($this->nab);
        return $this->roundDown($res, self::UNIT_BEHIND_COMMA);
    }

    public function storeBalance($id, Request $request) {
        $oldData = Customer::find($id);
    }

    public function withdrawBalance($id, Request $request) {

    }

    public function seeBalance($id) {

    }


    //
}
