<?php
namespace App\Traits\Address;

use App\Models\Address;
use Illuminate\Support\Facades\Validator;

trait setAddressTrait
{
    protected static $addressType = 1;

    protected $addressSelect = [
        "id as ky",
        "address_type",
        "public_place",
        "number",
        "complement",
        "zip_code",
        "district",
        "city",
        "state",
        "country"
    ];

    private static $validatorAddress = [
        "address_type" => "int",
        "public_place" => "required|string|min:3",
        "number" => "required",
        "zip_code" => "required|string|min:8|max:9",
        "district" => "required|string",
        "city" => "required|string",
        "state" => "required|string",
    ];

    private static $validatorAddressTranslate = [
        "address_type" => "Tipo de endereço inválido",
        "public_place" => "Informe o logradouro",
        "number" => "Informe o número",
        "zip_code" => "Informe o CEP",
        "district" => "Informe o bairro",
        "city" => "Informe a cidade",
        "state" => "Informe o estado",
    ];

    protected function validateAddress(?array $address)
    {
        return Validator::make($address, self::$validatorAddress, self::$validatorAddressTranslate);
    }

    public function setAddress(?array $address) : ?Address
    {
        $newAddress = null;
        if (!$this->validateAddress($address ?? [])->fails()) {
            $address["at_id"] = $this->id;
            $address["at_type"] = self::$addressType;
            if (isset($address["ky"])) {
                $lastAddress = Address::where(["id" => $address["ky"], "at_id" => $this->id, "at_type" => self::$addressType])->first();
                $lastAddress && $lastAddress->fill($address);
                if ($lastAddress && $lastAddress->isDirty()) {
                    $lastAddress->delete();
                }
            }
            unset($address["ky"]);
            $newAddress = Address::firstOrCreate($address);
        }
        return $newAddress;
    }

    public function deleteAllAddress() : bool
    {
        return $this->getAddress()->delete();
    }

    public function address() 
    {
        return $this->getAddress()->select($this->addressSelect);
    }
    
    public function getAddress() 
    {
        return $this->hasMany(Address::class, "at_id", "id")->where("at_type", self::$addressType);
    }
}
