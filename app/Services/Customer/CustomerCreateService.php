<?php
namespace App\Services\Customer;

use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use App\Services\Queue\Types\EmailWelcomeCustomer;
use Symfony\Component\HttpFoundation\Response AS HttpResponse;
use Illuminate\Support\Facades\Response;


class CustomerCreateService extends CustomerService
{
    protected $request ;

    protected $response = ["error" => "error creating client"] ;

    protected $responseStatus = HttpResponse::HTTP_BAD_GATEWAY ;

    const REQUEST_ONLY = ["name","email","birth_date", "password"];

    public function __construct(CustomerStoreRequest $request)
    {
        $this->request = $request ;
        $this->create() ; 
    }

    protected function verifyCustomer() : void
    {
        if (!$this->customer || !$this->customer->exists()) {
            $this->errors["user-not-exists"] = "Error creating user in the system";
        }
    }
    protected function create() : void
    {
        if ($this->valid()) {
            $this->customer = Customer::create($this->request->only(self::REQUEST_ONLY)) ;
            $this->verifyCustomer() ;
            if ($this->customer->exists()) {
                $this->customer->setAddress($this->request->address) ;
                $this->customer->setPhone($this->request->phone) ;
                EmailWelcomeCustomer::enqueue($this->customer) ;
            }
        }
    }

    public function json()
    {
        if ($this->valid()) {
            $this->customer->hidden([]);
            $this->response = ["success" => true, "customer" => $this->customer];
            $this->responseStatus = HttpResponse::HTTP_CREATED;
        }
        return Response::json($this->response, $this->responseStatus);
    }
}