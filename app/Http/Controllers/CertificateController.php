<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $certificates = Certificate::get();

        return view("certificate/main", compact("certificates"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store the certificate when the request is made at first time.
     * After this, you can then add more domains. 
     * @see addDomains()
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: validate the domain
        $domain = $request->domain;

        try {
            // generate the certificate and ensure to only save records in the database once the certificate is generated
            $certificate = new Certificate;

            $generate = $certificate->generate($domain);
            // $generate = $certificate->registerAccount();

            dd($generate);

            // if($generationResponse == "success") {
            //     $certificate->domain = $domain;
            //     $certificate->save();
            // }

        } catch(\Exception $e) {

            dd("Something went wrong");
        }

        return redirect()->route('certificate.main');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $certificate
     * @return \Illuminate\Http\Response
     */
    public function show(Certificate $certificate)
    {
        return view('certificate/show', compact('certificate'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Save additional domains to an existing certificate
    public function addDomains(Request $request, Certificate $certificate) 
    {
        dd($certificate);

    }
}
