<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Exception;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createCompany(Request $request)
    {
        //
        $company = new Company;
        $createdby = $request->createdby;
        $companyname= $request->companyname;
        $companylogo = $request->companylogo;
        $companyurl= $request->companyurl;
        if($companyname==""||$companylogo==""||$companyurl==""||$createdby==""){
            $company->responsecode= "87";
            $company->responsemessage= "createdby, companyname, companylogo, companyurl is required";
            return $company;
        }
        //check if user creating exist
        if (User::where('emailaddress', $createdby)->exists()){
            $user_creating=new User;
            $user_creating = User::where('emailaddress', $createdby)->get();
            $user_creating_role=$user_creating[0]->role;
            if(strtolower($user_creating_role)=='superadmin')
            {
                //create company
                $comcode=date("Ymdhis");
                $company->companyname = $request->companyname;
                $company->companylogo = $request->companylogo;
                $company->companyurl = $request->companyurl;
                $company->companycode = "CM$comcode";
                $company->createdby = $request->createdby;
                try{
                    $company->save();
                    $company->responsecode= "00";
                    $company->responsemessage= "Company created successfully";
                }catch(Exception $e){
                    $company->responsecode= "11";
                    $errormsg=$e->getMessage() ;
                    $company->responsemessage= "Could not create company. $errormsg";
                }

            }else{
                //not enough priviledge
                $company->responsecode= "22";
                $company->responsemessage= "You do not have sufficient priviledges to perform operation";
            }

        }else{
            //invalid request
            $company->responsecode= "99";
            $company->responsemessage= "Invalid Request. Please try again";

        }



        return $company;

    }

     public function getAllCompanies(Request $request)
        {
            $company = new Company;
            $emailaddress = $request->emailaddress;
            if($emailaddress==""){
                $company->responsecode= "87";
                $company->responsemessage= "emailaddress is required";
                return $company;
            }
            $allcompany= new Company;
            if (count(Company::get())>0){
                $allcompany =Company::get();
                $company->responsecode= "00";
                $company->responsemessage= "Records fetched successfully";
                $company->result= $allcompany;
            }else{
                $company->responsecode= "11";
                $company->responsemessage= "No record found";
            }

            return $company;
        }

    public function getCompanybyId(Request $request)
        {
            $company = new Company;
            $emailaddress = $request->emailaddress;
            $id = $request->id;
            if($emailaddress==""||$id==""){
                $company->responsecode= "87";
                $company->responsemessage= "emailaddress, id is required";
                return $company;
            }
            if (Company::where('id', $id)->exists()){
                $allcompany = Company::where('id', $id)->get();
                $company->responsecode= "00";
                $company->responsemessage= "Records fetched successfully";
                $company->result= $allcompany;
            }else{
                $company->responsecode= "11";
                $company->responsemessage= "No record found";
            }



            return $company;
        }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
