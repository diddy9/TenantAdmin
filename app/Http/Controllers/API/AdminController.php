<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\admin_users;
use Auth;
use Validator;

class AdminController extends Controller
{

    public function register(Request $req){

         $validator = Validator::make($req->all(),[
            'f_name' => ['required', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admin_users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

         if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $admin = admin_users::create([
            'f_name' => $req->f_name,
            'l_name' => $req->l_name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $admin,'access_token' => $token, 'token_type' => 'Bearer', ]);

    } 


    public function login(Request $request){
        if (!Auth::attempt($request->only('email', 'password'))){
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = admin_users::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->f_name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
    }

     public function all() {
        $responseData = admin_users::all();
        return response()->json($responseData);
    }
    
    public function delete($id){
        $user = admin_users::find($id);
        $user->deleted_at = Carbon::now()->toDateTimeString();
        $user->update();
        return response()
            ->json(['data' => $user]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
    





}
