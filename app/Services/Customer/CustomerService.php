<?php
namespace App\Services\Customer;

class CustomerService
{    
    protected $customer ;

    protected $errors = [] ;

    public function getErrors() : array
    {
        return $this->errors ;
    }

    public function valid() : bool
    {
        return count($this->errors) === 0 ;
    }
}