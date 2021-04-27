<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\NAB;
use DB;

class CustomerController extends Controller
{

    public $nab = 1;
    public const UNIT_BEHIND_COMMA = 4;
    public const BALANCE_BEHIND_COMMA = 2;
    
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

    public function getNabNow() {
        return NAB::latest()->first()->nab;
    }

    public function addCustomer(Request $request) {
        $name = $request->input('name');
        $username = $request->input('username');
        // rounding down
        $raw_balance = 0;
        $balance = $this->roundDown($raw_balance, self::BALANCE_BEHIND_COMMA);
        $raw_unit = $balance/($this->getNabNow());
        $unit = $this->roundDown($raw_unit, self::UNIT_BEHIND_COMMA);

        $res = Customer::create([
            'name' => $name,
            'username' => $username,
            'balance' => $balance,
            'unit' => $unit
        ]);

        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Customer succesfully added!',
                'data' => [
                    'id' => $res->id
                ]
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

    public function getTotalUnit() {
        $data = DB::table('customers')->sum('unit');
        return $data;
    }

    public function changeUnit($oldBalance) {
        $res = $oldBalance/($this->getNabNow());
        return $this->roundDown($res, self::UNIT_BEHIND_COMMA);
    }

    public function storeBalance(Request $request) {
        $id = $request->input('user_id');
        $data = Customer::find($id);
        $balanceStored = $request->input('amount_rupiah');
        // update balance
        $data->balance += $balanceStored;
        $unitAdded = $this->changeUnit($balanceStored);
        $data->unit = $data->unit + $unitAdded;
        $res = $data->save();
        

        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Your balance is stored',
                'data' => [
                    'nilai_unit_hasil_topup' => $unitAdded,
                    'nilai_unit_total' => $data->unit,
                    'saldo_rupiah_total' => $data->balance
                ]
            ], 200);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Your balance is not stored',
                'data' => ''
            ], 400);
        }
    }

    public function withdrawBalance(Request $request) {
        $id = $request->input('user_id');
        $data = Customer::find($id);
        $balanceWithdrawn = $request->input('amount_rupiah');
        // update balance
        $data->balance -= $balanceWithdrawn;
        $unitWithdrawn = $this->changeUnit($balanceWithdrawn);
        $data->unit = $data->unit - $unitWithdrawn;

        if ($data->balance < 0) {
            return response()->json([
                'success' => false,
                'message' => 'You are withdrawing too much',
                'data' => ''
            ], 400);
        }
        $res = $data->save();
        

        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Your balance is withdrawn',
                'data' => [
                    'nilai_unit_setelah_withdraw' => $unitWithdrawn,
                    'nilai_unit_total' => $data->unit,
                    'saldo_rupiah_total' => $data->balance
                ]
            ], 200);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Your balance is not withdrawn',
                'data' => ''
            ], 400);
        }
    }

    public function seeBalance($id) {

    }


    //
}
