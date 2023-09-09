<?php

namespace App\Rules;

use App\Models\Country;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class ValidPhoneNumber implements ValidationRule
{
    protected $country;

    public function __construct($country_id)
    {
        $Country = Country::where('id', $country_id)->first();
        if($Country){
            $this->country = strtoupper($Country->code);
        }else{
            $this->country = null;
        }
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneNumberUtil->parse($value, $this->country);
            if(!$phoneNumberUtil->isValidNumberForRegion($phoneNumber, $this->country)){
                $fail(__('validations.phone'));
            }
        } catch (NumberParseException $e) {
            $fail(__('validations.phone'));
        }
    }
}
