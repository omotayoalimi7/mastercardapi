<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
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
    public function createUser(Request $request)
    {
        //
        $user = new User;
        $createdby = $request->createdby;
        $emailaddress= $request->emailaddress;
        $firstname = $request->firstname;
        $lastname= $request->lastname;
        $newuserrole= $request->role;
        if($firstname==""||$lastname==""||$emailaddress==""||$newuserrole==""||$emailaddress==""){
            $user->responsecode= "87";
            $user->responsemessage= "createdby, firstname, lastname, emailaddress, role is required";
            return $user;
        }
        if (User::where('emailaddress', $createdby)->exists()){
            //user making request exists, proceed
            $user_creating=new User;
            $user_creating = User::where('emailaddress', $createdby)->get();
            $user_creating_role=$user_creating[0]->role;
            if(strtolower($user_creating_role)=='superadmin'||strtolower($user_creating_role)=='admin')
            {
                //user making request has sufficient role to proceed
                if (User::where('emailaddress', $emailaddress)->exists()){
                    //user to be created already exist
                    $user_to_create = User::where('createdby', $emailaddress)->get();
                    $user->responsecode= "11";
                    $user->responsemessage= "Email address exists";
                }else{
                    //create user
                    $hashedpassword = Hash::make($request->password);

                    $user->emailaddress = $request->emailaddress;
                    $user->firstname = $request->firstname;
                    $user->lastname = $request->lastname;
                    $user->companycode = $request->companycode;
                    $user->role = $request->role;
                    $user->profilestatus = 'Active';
                    $user->password = $hashedpassword;
                    $user->createdby = $request->createdby;
                    try{
                        $user->save();
                        $user->responsecode= "00";
                        $user->responsemessage= "User created successfully";
                    }catch(Exception $e){
                        $user->responsecode= "11";
                        $errormsg=$e->getMessage() ;
                        $user->responsemessage= "Could not create user. $errormsg";
                    }


                }
            }else{
                //user making request does not have sufficient role to proceed
                $user->responsecode= "22";
                $user->responsemessage= "You do not have sufficient priviledges to do this.";
            }

        }else{
            //invalid request, emailaddress creating request not recognised
            $user->responsecode= "99";
            $user->responsemessage= "Invalid Request. Please try again";


        }
        return $user;

    }


    public function LoginUser(Request $request)
        {
            //
            $user = new User;
            $password = $request->password;
            $emailaddress= $request->emailaddress;
            if($password==""||$emailaddress==""){
                $user->responsecode= "87";
                $user->responsemessage= "password, emailaddress is required";
                return $user;
            }
            if (User::where('emailaddress', $emailaddress)->exists()){
                //user making request exists, proceed
                $user_login=new User;
                $user_login = User::where('emailaddress', $emailaddress)->get();
                $user_login_pass=$user_login[0]->password;
                if (Hash::check($password, $user_login_pass)){
                     $user=$user_login[0];
                     $user->responsecode= "00";
                     $user->responsemessage= "Login Successful";

                }else{
                    $user->responsecode= "11";
                    $user->responsemessage= "Invalid Emailaddress/Password";
                }



            }else{
                //invalid request, emailaddress creating request not recognised
                $user->responsecode= "99";
                $user->responsemessage= "Invalid Emailaddress/Password";


            }
            return $user;

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
    public function updateUser(Request $request)
    {
        $user = new User;
        $firstname = $request->firstname;
        $id = $request->id;
        $lastname= $request->lastname;
        if($firstname==""||$lastname==""||$id==""){
            $user->responsecode= "87";
            $user->responsemessage= "firstname, lastname, id is required";
            return $user;
        }
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
            $user->firstname = is_null($request->firstname) ? $user->firstname : $request->firstname;
            $user->lastname = is_null($request->lastname) ? $user->lastname : $request->lastname;
            try{
                $user->save();
                $user->responsecode= "00";
                $user->responsemessage= "Profile edited successfully";
            }catch(Exception $e){
                $user->responsecode= "11";
                $errormsg=$e->getMessage() ;
                $user->responsemessage= "Could not edit Profile. $errormsg";
            }

        } else {
            $user->responsecode= "11";
            $user->responsemessage= "Could not find User.";

        }
        return $user;
    }

    public function getCompanyusers(Request $request)
        {
            $user = new User;
            $companycode = $request->companycode;
            if($companycode==""){
                $user->responsecode= "87";
                $user->responsemessage= "companycode is required";
                return $user;
            }
            if (User::where('companycode', $companycode)->exists()) {
                $allusers= new User;
                $allusers =User::where('companycode', $companycode)->get();
                $user->responsecode= "00";
                $user->responsemessage= "Records fetched successfully";
                $user->result= $allusers;
            } else {
                $user->responsecode= "11";
                $user->responsemessage= "No user in company.";

            }
            return $user;
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
