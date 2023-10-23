<?php
namespace App\Traits\Phone;

use App\Models\Phone;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

trait SetPhoneTrait
{
    protected static $phoneType = 1;

    protected $phoneSelect = [
        "id as ky",
        "phone_type",
        "number",
    ];

    private static $validatorPhone = [
        "phone_type" => "int",
        "number" => "required|regex:/^\+?\d+$/",
    ];

    private static $validatorPhoneTranslate = [
        "phone_type" => "Tipo do telefone inválido",
        "number" => "Informe o número",
    ];

    protected function validatePhone(?array $phone)
    {
        return Validator::make($phone, self::$validatorPhone, self::$validatorPhoneTranslate);
    }

    public function setPhone(?array $phone) : ?Phone
    {
        $newPhone = null;
        if (!$this->validatePhone($phone ?? [])->fails()) {
            $phone["at_id"] = $this->id;
            $phone["at_type"] = self::$phoneType;
            if (isset($phone["ky"])) {
                $lastPhone = Phone::where(["id" => $phone["ky"], "at_id" => $this->id, "at_type" => self::$phoneType])->first();
                $lastPhone && $lastPhone->fill($phone);
                if ($lastPhone && $lastPhone->isDirty()) {
                    $lastPhone->delete();
                }
            }
            unset($phone["ky"]);
            $newPhone = Phone::firstOrCreate($phone);
        }
        return $newPhone;
    }

    public function deleteAllPhone() : bool
    {
        return $this->getPhone()->delete();
    }

    public function phone() 
    {
        return $this->getPhone()->select($this->phoneSelect);
    }
    
    public function getPhone() 
    {
        return $this->hasMany(Phone::class, "at_id", "id")->where("at_type", self::$phoneType);
    }
}
