<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function registerAccount(){
    //     $register = exec("php ../acmephp.phar register esp.sousa@gmail.com");
    //     // $bcinfo = exec("php ../acmephp.phar authorize bcminfo.de");
    //     dd($register);
    // }

    public function generate($mainDomain) {

        $register = exec("php ../acmephp.phar register esp.sousa@gmail.com");
    
        try {
    
            $authResponse = $this->certificateAuthorization($mainDomain);
            $this->certificateChallenge($authResponse);
            
            $certificateRequestCheck = $this->certificateRequestCheck($mainDomain);

            $certificateRequest = $this->certificateRequest($mainDomain);
    
            dd($certificateRequest);

        } catch(\Exception $e) {
    
            var_dump($e->getMessage());
    
        }
    }

    public function certificateAuthorization($mainDomain) {

        $authResponse = shell_exec("php ../acmephp.phar authorize {$mainDomain} -n");
        $successMessage = "The authorization tokens was successfully fetched!";

        // if(Str::contains($authResponse, $successMessage)){
        //     return true;
        // }
        
        return $authResponse;
    }

    public function certificateChallenge($authResponse) {

        $matches = [];
        preg_match_all('/{(.*?)}}/', $authResponse, $matches);

        foreach($matches[0] as $match) {

            $data = json_decode($match);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://'. $data->domain .'/letsencrypt/token?token=' . $data->challenge->token . '&payload=' . $data->challenge->payload,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
            ));

            $response = curl_exec($curl);

            curl_close($curl);
        }

    }


    public function certificateRequestCheck($mainDomain) {

        $check = shell_exec("php ../acmephp.phar check {$mainDomain}");
        $successMessage = 'The authorization check was successful!';

        // if(Str::contains($check, $successMessage)){
        //     dd($successMessage);
        // }
        return $check;
    }

    public function certificateRequest($mainDomain) {

        try {

            $request = shell_exec("php ../acmephp.phar request {$mainDomain}");
            return $request;

        } catch(\Exception $e) {
    
            var_dump($e->getMessage());
    
        }
    }
}
