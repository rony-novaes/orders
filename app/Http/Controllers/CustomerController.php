<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use App\Services\Customer\CustomerCreateService;
use App\Services\Queue\Types\EmailWelcomeCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customer = Customer::select(["id","name", DB::raw("DATE_FORMAT(birth_date, '%d-%m-%Y') as birth"),"email","created_at as registration_date"]) ;
        
        if ($request->has("email") ) {
            $customer->where("email", "like", sprintf("%%%s%%", $request->input("email"))) ;
        }

        if ($request->has("name") ) {
            $customer->where("name", "like", sprintf("%%%s%%", $request->input("name"))) ;
        }

        return Response::json($customer->paginate($request->perPage ?? 10)) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request)
    {
        return (new CustomerCreateService($request))->json();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Customer $customer)
    {
        $customer->hidden(["created_at", "deleted_at", "updated_at"]);
        
        if ($request->has("address")) {
            $customer->address = $customer->address;
        }

        if ($request->has("phone")) {
            $customer->phone = $customer->phone;
        }

        return Response::json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $response = ["error" => "error when update customer"];
        $responseStatus = HttpFoundationResponse::HTTP_NOT_MODIFIED;

        if ($customer->update($request->all())) {
            
            $customer->setAddress($request->address);
            $customer->setPhone($request->phone);

            $response = ["success" => true];
            $responseStatus = HttpFoundationResponse::HTTP_OK;
        }

        return Response::json($response, $responseStatus);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $response = ["error" => "error when deleting customer"];
        $responseStatus = HttpFoundationResponse::HTTP_NOT_MODIFIED;

        $delete = $customer->delete();
        $response["messages"] = $delete->getErrors() ;
        if ($delete->deleted()) {
            $response = ["success" => true];
            $responseStatus = HttpFoundationResponse::HTTP_ACCEPTED;
        }

        return Response::json($response, $responseStatus);
    }
}
