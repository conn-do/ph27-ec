<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $address = $request->user()->address;

        return view('address', [
            'address' => $address,
        ]);
    }

    public function edit(Request $request)
    {
        $address = $request->user()->address;

        return view('address_edit', [
            'address' => $address,
        ]);
    }

    public function store(Request $request)
    {
        $address = $request->user()->address;

        if (!$address) {
            $address = new Address();
            $address->user_id = $request->user()->id;
        }

        $address->postal_code = $request->postal_code;
        $address->address = $request->address;
        $address->save();

        return view('address_complete');
    }
}