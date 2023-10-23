<?php
namespace App\Services\Customer;

use App\Models\Customer;
use App\Services\Queue\Types\EmailGodbyeCustomer;
use Illuminate\Support\Facades\DB;


class CustomerDeleteService extends CustomerService
{
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;    
    }

    public function deleted() : bool
    {
        return $this->customer->exists() ;
    }

    public function verifyCustomer() : void
    {
       
        if (!$this->customer->exists()) {
            $this->errors["user-not-exists"] = "User does not exist in the system";
        }
    }

    public function verifyOrders() : void
    {
        
    }
    
    public function effectiveDeletion() : void
    {
        if ($this->valid()) {
            DB::beginTransaction();
            $deleteAddress = true;
            if ($this->customer->getAddress()->count() && !$this->customer->deleteAllAddress()) {
                $deleteAddress = false;
            }
            if (!($deleteAddress && $this->customer->deleteForce())) {
                DB::rollback();
                return;
            }
            EmailGodbyeCustomer::enqueue($this->customer) ;
            DB::commit();
        }
    }

    public function delete()
    {
        $this->verifyCustomer();
        $this->verifyOrders();
        $this->effectiveDeletion();
        return $this;
        // Regra de delete
        // Não pode haver Pedido em abertos
        // Não pode haver Pendência
        // Deletar todos os endereços
        // Os pedidos que foi finalizado com sucesso, não será excluido, pois é utilizado como base para relatórios
        $success = $this->verifyOrders();
    }
}