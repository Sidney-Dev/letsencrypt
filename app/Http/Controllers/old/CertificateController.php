<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('certificates/main');
    }

    public function create()
    {
        return view('list');
    }

    public function store(Request $request)
    {
        $domain = $request->input("domain");

        // $this->registerAccount();
        // $authorizeRequest = $this->authorizeRequest($domain);
        // $domainChecker = $this->domainChecker($domain);

        // $requestSingleRequest = $this->requestSingleRequest($domain);
        
        $multDomainRequest = $this->addMultDomains("bcm-testing01.de", $domain);

        dd($multDomainRequest);

        return redirect('/certificate/list');
    }

    public function registerAccount()
    {
        $register = exec("php ../../../acmephp.phar register esp.sousa@gmail.com");
        return $this;
    }

    // request authorization
    public function authorizeRequest($mainDomain, $additionalDomains = "") 
    {
        // run authorization
        if(empty($additionalDomains)) {
            $authResponse = shell_exec("php ../../../acmephp.phar authorize {$mainDomain} -n");
        } else {
            $authResponse = shell_exec("php ../../../acmephp.phar authorize {$mainDomain} {$additionalDomains} -n");
        }

        $matches = [];
        $domainsToAuthorize = [];
        preg_match_all('/{(.*?)}}/', $authResponse, $matches);
        foreach($matches[0] as $match) {

            $data = json_decode($match);
            $domainsToAuthorize[$data->domain]['token']   = $data->challenge->token;
            $domainsToAuthorize[$data->domain]['payload'] = $data->challenge->payload;

            $curl = curl_init();

            /**
             * DNA challenge
             */
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
        return $domainsToAuthorize;
    }

    // prove that you own the domain
    public function domainChecker($mainDomain, $addonDomainsAuthorize = "") 
    {
        $check = shell_exec("php ../../../acmephp.phar check -s http {$mainDomain} {$addonDomainsAuthorize} -n");

        return $check;

    }

    // requests certificate for one domain. This is used during the initialization phase
    public function requestSingleRequest($mainDomain) {
        
        $request = shell_exec("php ../../../acmephp.phar request {$mainDomain}");
        
        return $request;
    }

    /**
     * Add multiple domains to the same certificate
     */
    public function addMultDomains($mainDomain = "bcm-testing01.de", $additionalDomains = "bcminfo.de") {

        $additionalDomainsArray = explode(",", $additionalDomains);

        // additional domains used during authorization
        $addonDomainsAuthorize = implode(" ", $additionalDomainsArray);
        
        $convertedDomainsArray = [];

        foreach($additionalDomainsArray as $value) {
            array_push($convertedDomainsArray, "-a $value");
        }
        
        // additional domains used during the request
        $additionalDomains = implode(" ", $convertedDomainsArray);

        $registeredAccount = $this->registerAccount();

        /**
         * Validation tip: Should return an array
         */
        $authorization = $this->authorizeRequest($mainDomain, $additionalDomains);
        
        /**
         * Validation tip: Find the proper text that guarantees that the check was successfull.
         *            The authorization check was successful
         */
        $checkDomain = $this->domainChecker($mainDomain, $additionalDomains);

        /**
         * Validation tip: a wrong response results in an empty arrray
         *             success message: The SSL certificate was fetched successfully!
         */
        $sslRequest = shell_exec("php ../../../acmephp.phar request {$mainDomain} {$additionalDomains}");

        return $sslRequest;
    }

    public function addMany() {

        $register = exec("php acmephp.phar register esp.sousa@gmail.com");
    
        try {
    
            $mainDomain = "bcm-testing01.de";
            $additionalDomains = "bcminfo.de";
    
            $additionalDomainsArray = explode(",", $additionalDomains);
            $addonDomainsAuthorize = implode(" ", $additionalDomainsArray); 
            $convertedDomainsArray = [];
    
            foreach($additionalDomainsArray as $value) {
                array_push($convertedDomainsArray, "-a $value");
            }
            
            // additional domains used during the request
            $addonDomainsRequest = implode(" ", $convertedDomainsArray);
    
            $authResponse = shell_exec("php ../../../acmephp.phar authorize {$mainDomain} {$addonDomainsAuthorize} -n");
            
            $matches = [];
            preg_match_all('/{(.*?)}}/', $authResponse, $matches);
            foreach($matches[0] as $match) {
    
                $data = json_decode($match);
    
                $curl = curl_init();
    
                /**
                 * Check
                 */
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
    
            $check = shell_exec("php ../../../acmephp.phar check {$mainDomain} {$addonDomainsAuthorize}");
    
            $request = shell_exec("php ../../../acmephp.phar request {$mainDomain} {$addonDomainsRequest}");
    
            dd($request);

        } catch(\Exception $e) {
    
            var_dump($e->getMessage());
    
        }
    }


    public function addOne() {

        $register = exec("php acmephp.phar register esp.sousa@gmail.com");
    
        try {
    
            $mainDomain = "bcminfo.de";
    
            $authResponse = shell_exec("php ../../../acmephp.phar authorize {$mainDomain} -n");
            
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
    
            $check = shell_exec("php ../../../acmephp.phar check {$mainDomain}");
    
            $request = shell_exec("php ../../../acmephp.phar request {$mainDomain}");
    
            dd($request);

        } catch(\Exception $e) {
    
            var_dump($e->getMessage());
    
        }
    }


    /**
     * Create
     */
    public function new(Request $request) 
    {
        $domain = $request->input("domain");

        dd($domain);
    }


}


