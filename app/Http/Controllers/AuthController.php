<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
// use App\Models\User;
use App\Mail\WelcomeEmail;
use App\Mail\NewsLetterMail;
use Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function newsletter(Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);
        file_put_contents('newsletter.txt', $request->email.PHP_EOL, FILE_APPEND);
         Mail::to($request->email)->send(new NewsLetterMail());

        return response()->json(['message'=>'Newsletter subscription successful!']);
     }
    public function register(Request $request) {
        $request->validate([
            'name' => 'nullable',
            'accountType'=>'required|string|in:private,company',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'telephone' => 'required',
            'companyName' => 'nullable',
            'vatNumber' => 'nullable',
            'address' => 'nullable',
            'invoicingCode' => 'nullable',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request->name??'',
            'accountType' => $request->accountType,
            'email' => $request->email,
            'username' => $request->username,
            'telephone' => $request->telephone,
            'companyName' => $request->companyName,
            'vatNumber' => $request->vatNumber,
            'address' => $request->address,
            'invoicingCode' => $request->invoicingCode,
            'password' => bcrypt($request->password),
        ]);
         Mail::to($user->email)->send(new WelcomeEmail($user));
        return response()->json(['user' => $user,'message'=>'Account created successfully!']);
    }

        
    public function login(Request $request) {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $user = User::where('username', $request->username)
        ->orWhere('email', $request->username)
        ->first();    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        // Attempt login
        $token = $user->createToken('api-token')->plainTextToken;

    
        // Debug statement to check if the login was successful
         
            return response()->json([
                'token'=>$token,
                'user' => $user, // Return the user object directly
                'message' => 'You have been logged in successfully!'
            ]);
        
    }
    
    
    

    public function logout(Request $request) {
        Auth::guard('web')->logout();
        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request) {
        return response()->json($request->user());
    }
    public function update(Request $request) {
        // return $request->user();
        $request->validate([
            'name' => 'nullable',
            'accountType'=>'required|string|in:private,company',
             'telephone' => 'required',
            'companyName' => 'nullable',
            'vatNumber' => 'nullable',
            'address' => 'nullable',
            'invoicingCode' => 'nullable',
         ]);

        $user = $request->user()->update([
            'name' => $request->name??'',
            'accountType' => $request->accountType,
             'telephone' => $request->telephone,
            'companyName' => $request->companyName,
            'vatNumber' => $request->vatNumber,
            'address' => $request->address,
            'invoicingCode' => $request->invoicingCode,
         ]);

        return response()->json(['user' => $user,'message'=>'Account updated successfully!']);
    }
    
function generateUniqueUsername($base)
{
    // Slugify the base string (e.g., "John Doe" => "john-doe")
    $username = Str::slug($base);

    // Check if it already exists
    $original = $username;
    $i = 1;

    while (User::where('username', $username)->exists()) {
        $username = str_replace('-','',$original . '-' . $i);
        $i++;
    }

    return $username;
}
    public function google_socialite(){
        $googleUser = Socialite::driver('google')->stateless()->user();

        // $googleUser->getEmail();
        // $user = \App\Models\User::firstOrCreate(
        //     ['email' => $googleUser->getEmail()],
        //     [
        //         'name' => $googleUser->getName(),
        //         'password' => bcrypt(\Illuminate\Support\Str::random(16)),
        //     ]
        // );
        // Check if user is already
        $User = User::where('email',$googleUser->getEmail())->first();
        if($User){
            $token = $User->createToken('api-token')->plainTextToken;
            return redirect(env('FRONTEND_URL').'google_auth?token='.$token);
        }
        $baseName = $googleUser->getNickname() ?: $googleUser->getName() ?: Str::before($googleUser->getEmail(), '@');
        $username = self::generateUniqueUsername($baseName);
        $User = User::create([
            'name' => $googleUser->getName()??'',
            'accountType' => 'private',
            'email' => $googleUser->getEmail(),
            'username' => $username,
            'telephone' => '0',
            'companyName' =>'',
            'vatNumber' => '',
            'address' => '',
            'invoicingCode' => '',
            'password' => bcrypt(123456654321),
        ]);
        $token = $User->createToken('api-token')->plainTextToken;
        return redirect(env('FRONTEND_URL').'google_auth?token='.$token);

    }

    public function facebook_socialite(){
        $googleUser = Socialite::driver('facebook')->stateless()->user();

        // $googleUser->getEmail();
        // $user = \App\Models\User::firstOrCreate(
        //     ['email' => $googleUser->getEmail()],
        //     [
        //         'name' => $googleUser->getName(),
        //         'password' => bcrypt(\Illuminate\Support\Str::random(16)),
        //     ]
        // );
        // Check if user is already
        $User = User::where('email',$googleUser->getEmail())->first();
        if($User){
            $token = $User->createToken('api-token')->plainTextToken;
            return redirect(env('FRONTEND_URL').'google_auth?token='.$token);
        }
        $baseName = $googleUser->getNickname() ?: $googleUser->getName() ?: Str::before($googleUser->getEmail(), '@');
        $username = self::generateUniqueUsername($baseName);
        $User = User::create([
            'name' => $googleUser->getName()??'',
            'accountType' => 'private',
            'email' => $googleUser->getEmail(),
            'username' => $username,
            'telephone' => '0',
            'companyName' =>'',
            'vatNumber' => '',
            'address' => '',
            'invoicingCode' => '',
            'password' => bcrypt(123456654321),
        ]);
        $token = $User->createToken('api-token')->plainTextToken;
        return redirect(env('FRONTEND_URL').'google_auth?token='.$token);

    }
    
}
