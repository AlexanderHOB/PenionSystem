<?php
namespace App\Http\Controllers;
use App\{User,Persona};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
            'color'    => 'required|string|max:7'
        ]);
        $user = new User([
            'id'            => $request->id,
            'email'         => $request->email,
            'color'         => $request->color,
            'empleado_id'   => $request->empleado_id,
            'rol_id'        => $request->rol_id,
            'password' => bcrypt($request->password),
        ]);
        $user->save();
        // return response()->json([
        //     'message' => 'Successfully created user!'], 201);
        $user->empleado->persona;
        $user->rol;
        return $user;
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'], 401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' =>
            'Successfully logged out']);
    }

    public function user(Request $request)
    {
        $user= $request->user();    
        $empleado=Persona::join('empleados','personas.id','=','empleados.id')
        ->join('users','users.empleado_id','=','personas.id')
        ->join('roles','users.rol_id','=','roles.id')
        ->select(DB::raw("CONCAT(personas.nombres,roles.nombre) AS fullname"))
        ->where('personas.id','=',$user->empleado_id)
        ->get();
        
        return response()->json($empleado);
    }
}
