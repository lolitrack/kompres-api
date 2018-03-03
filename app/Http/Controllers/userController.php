<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;



class userController extends Controller
{
    public function index(){
    	$show = User::all();
    	return $show;
    }

    public function update(Request $request){         
        $updated =$request->user();
        // kalo mkodel dan table user
        // $updated =JWTAuth::parseToken()->authenticate();
        //kalo bukan user nama table dan modelnya pake yang ini
        $updated->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'pendidikan' => $request->pendidikan, 
            'telp' => $request->telp, 
            'role' => $request->role
        ]);
        return $updated;
    }

    public function createFacilitator(Request $request){
       if($request->user()->role == 1){
         $facilitator = $request->user()->facilitator()->create([
            "nama_instansi" => $request->nama_instansi,
            "deskripsi_instansi"=> $request->deskripsi_instansi
        ]);
        
        return $facilitator;
    }
    return "Kamu tidak memiliki hak untuk ini";
       
    }

    public function signup(Request $request){
    	$this->validate($request, [
    		'username' => 'required|unique:users',
    		'email' => 'required|unique:users',
    		'password' => 'required',
    	]);

    	$create = User::create([
    		 'username' => $request->username,
    		 'email' => $request->email,
    		 'password' => bcrypt($request->password)
    		]);

    	return $create;

    }

    public function signin(Request $request){
    	$this->validate($request, [
    		'username' => 'required',
    		'password' => 'required',
    	]);

    	// grab credentials from the request
        $credentials = $request->only('username', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
}
