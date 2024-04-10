<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\App as APIApp;
use Throwable;

class AccessTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request. 
     * Allow access where app ID & Secret match in the database
     */
    public function authorize(): bool
    {
        try{
            //parse the authorization header into app id and secret
            $auth = $this->header('Authorization');
            $identifiers = explode('=', base64_decode($auth));

            $app_id = $identifiers[0];
            $app_secret = $identifiers[1];
            //Make sure the app & secret match
            $appExists = APIApp::whereAppAccessId($app_id)->whereAppSecret($app_secret)->count();

            //If the app ID exists, move it into the request header so we can use it in the controller
            //without repeating code.
            if($app_id){
                $this->merge(['app_id'=>$app_id]);
            }
            //Allow access
            return $appExists === 1;
        }
        catch(Throwable $t){
            report($t->getMessage());
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        ];
    }
}
