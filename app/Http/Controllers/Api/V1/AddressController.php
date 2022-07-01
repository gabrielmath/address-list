<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\City;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $addresses = Address::query()->with('city')->get();
        return response()->json($addresses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddressRequest $request
     * @return AddressResource
     */
    public function store(AddressRequest $request)
    {
        $city = City::with('addresses')->find($request->validated('cidade_id'));

        $newAddress = $city->addresses()->create([
            'street'       => $request->validated('logradouro'),
            'number'       => $request->validated('numero'),
            'neighborhood' => $request->validated('bairro'),
        ]);

        return new AddressResource($newAddress);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Address $address
     * @return AddressResource
     */
    public function show(Address $address)
    {
        return new AddressResource($address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AddressRequest $request
     * @param \App\Models\Address $address
     * @return AddressResource
     */
    public function update(AddressRequest $request, Address $address)
    {
        $address->update([
            'city_id'      => $request->validated('cidade_id'),
            'street'       => $request->validated('logradouro'),
            'number'       => $request->validated('numero'),
            'neighborhood' => $request->validated('bairro'),
        ]);

        return new AddressResource($address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Address $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Address $address)
    {
        $address->delete();

        return response()->json(['status' => 'OK', 'message' => 'Address deleted'], 204);
    }
}
