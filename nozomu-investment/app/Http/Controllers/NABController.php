<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\NAB;

class NABController extends Controller
{
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

    public function roundDown($val, $behindComma) {
        return floor($val * pow(10, $behindComma)) / pow(10,$behindComma);
    }

    public function list() {
        $data = NAB::all('nab', 'updated_at AS date');
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Listed',
                'data' => $data
            ], 200);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Not listed',
                'data' => ''
            ], 404);
        }
    }

    public function updateTotalBalance(Request $request) {
        $tot_balance = $request->input('current_balance');
        $tot_unit = (new CustomerController)->getTotalUnit();
        $nab = 1;
        if ($tot_unit != 0) {
            $raw_nab = $tot_balance/$tot_unit;
            $nab = $this->roundDown($raw_nab, self::NAB_BEHIND_COMMA);
        }
        $res = NAB::create([
            'nab' => $nab
        ]);
        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Total balance updated',
                'data' => [
                    'nab_amount' => $res->nab
                ]
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Total balance not updated',
                'data' => ''
            ], 400);
        }

    }

    //
}
