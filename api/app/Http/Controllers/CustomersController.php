<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customers\StoreCustmerRequest;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerPhone;
use App\Services\CustomerAddressService;
use App\Services\CustomerPhoneService;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $customers = Customer::paginate(10);

            return response()->json($customers);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustmerRequest $request)
    {
        try {

            $data = $request->all();

            return response()->json(CustomerService::store($data));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        try {

            return response()->json($customer);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        try {

            $data = $request->all();

            return response()->json(CustomerService::update($data, $customer));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try {

            $customer->delete();

            return response()->json();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function restore(Customer $customer) 
    {
        try {

            $customer->restore();

            return response()->json();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function listPhones(Customer $customer)
    {
        try {

            $phones = CustomerPhone::where('customer_id', $customer->id)->paginate(10);

            return response()->json($phones);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storePhone(Request $request, Customer $customer)
    {
        try {

            $data = $request->all();

            return response()->json(CustomerPhoneService::store($data, $customer));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function showPhone(Customer $customer, CustomerPhone $customerPhone)
    {
        try {

            return response()->json($customerPhone);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePhone(Request $request, Customer $customer, CustomerPhone $customerPhone)
    {
        try {

            $data = $request->all();

            return response()->json(CustomerPhoneService::update($data, $customer, $customerPhone));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroyPhone(Customer $customer, CustomerPhone $customerPhone)
    {
        try {

            $customerPhone->delete();

            return response()->json();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function restorePhone(Customer $customer, CustomerPhone $customerPhone)
    {
        try {

            $customerPhone->restore();

            return response()->json();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function listAddress(Customer $customer)
    {
        try {

            $phones = CustomerAddress::where('customer_id', $customer->id)->paginate(10);

            return response()->json($phones);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeAddress(Request $request, Customer $customer)
    {
        try {

            $data = $request->all();

            return response()->json(CustomerAddressService::store($data, $customer));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function showAddress(Customer $customer, CustomerAddress $customerAddress)
    {
        try {

            return response()->json($customerAddress);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateAddress(Request $request, Customer $customer, CustomerAddress $customerAddress)
    {
        try {

            $data = $request->all();

            return response()->json(CustomerAddressService::update($data, $customer, $customerAddress));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroyAddress(Customer $customer, CustomerAddress $customerAddress)
    {
        try {

            $customerAddress->delete();

            return response()->json();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function restoreAddress(Customer $customer, CustomerAddress $customerAddress)
    {
        try {

            $customerAddress->restore();

            return response()->json();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
