<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use App\Models\TenantProperties;
use App\Models\Hostname;
use App\Models\User;
use App\Models\Profile;
use Carbon\Carbon;
use Validator;
use Symfony\Component\HttpFoundation\Response as ResponseConstant;

class TenantController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(): JsonResponse {
        $responseData = Hostname::with('tenantProperties')->get();
        return response()->json($responseData, ResponseConstant::HTTP_OK);
    }

    public function create(Request $req){
        $url_base = config('app.url_base');
        $fqdn = "{$req->account}.{$url_base}";
        $req->merge(['fqdn'=>$fqdn]);
        $invalidSubdomains = config('app.invalid_subdomains');
        $validator = Validator::make($req->all(),[
            'account' => ['required', 'string',
                Rule::notIn( $invalidSubdomains ),
                'regex:/^[A-Za-z0-9](?:[A-Za-z0-9\-]{0,61}[A-Za-z0-9])$/'
            ],
            'fqdn'    => ['required', 'string', 'unique:hostnames'],
            'fname'   => ['required', 'string', 'max:255'],
            'lname'   => ['required', 'string', 'max:255'],
            'email'   => ['required', 'string', 'email', 'max:250'],
            'name'    => ['required', 'string', 'max:255'],
            'package' => ['required', 'string'],
            'days'    => ['required', 'numeric']
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $tenant = Hostname::create([
            'fqdn' => $req->fqdn,
            'created_by' => 1,
        ]);
        
        $user = User::create([
            'tenant_id' => $tenant->id,
            'f_name' => $req->fname,
            'l_name' => $req->lname,
            'email' => $req->email,
            'role_id' => 3,
            'password' => Hash::make("Pass123$$"),
        ]);

        $profile = Profile::create([ 
            'tenant_id' => $tenant->id,
            'user_id' => $user->id
        ]);

        $properties = TenantProperties::create([ 
            'hostname_id' => $tenant->id,
            'name' => $req->name,
            'subscribed_on' => Carbon::now()->toDateTimeString(),
            'days' => $req->days,
        ]);

        return response()
            ->json(['data' => $user]);
    }

    public function view($id): JsonResponse {
        $responseData = Hostname::with('tenantProperties')->find($id);
        return response()->json($responseData, ResponseConstant::HTTP_OK);
    }

    public function update(Request $req,$id){
        $tenant = TenantProperties::find($id);
        $tenant->update($req->all());
        return response()
            ->json(['message' => 'Tenant updated successfully']);

    }

    public function delete($id){
        $tenant = Hostname::find($id);
        $tenant->deleted_at = Carbon::now()->toDateTimeString();
        $tenant->update();
        return response()
            ->json(['data' => $tenant]);
    }




}
