<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\User;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/admin/listpost';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /*
        Standar login laravel
    */
    public function login_au(Request $request) // overwrite function login.
    {
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'email';
        
        if(auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password'])))
        {
            $user = Auth::user();
            if(!is_null($user->is_admin)){
                switch ($user->is_admin) {
                    case '1':
                        return redirect()->route('backend.dashboard')->with(['messenge'=>'Wellcome Admin']);
                        break;
                    
                    default:
                        echo "Bạn là thành viên mới sinh nek";
                        //return redirect()->route('home');
                        break;
                }
            }else{
                return redirect()->route('home')->with(['messenge'=>'Wellcome Member']);
            }
        }else{
            return redirect()->route('login')
                ->with('error','Email-Address And Password Are Wrong.');
        }       
    }
    /*
        login with eco touch acount
    */
    public function login(request $request){  
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        $credential =   $request->except(['_token']);
        //  check status account
        $step1      =   $this->_login_1($request->email,$request->password);
        //print_r($step1);
        session()->put('profile', [ 'email' => $request->email,   'password' => $request->password] );
        if(isset($step1->statusCode)){ 
            return redirect()->route('login')->with(['messenge'=>trim($step1->message)]);
        }else{
            if($step1->result==1){
                $step2 = $this->_login_2($request->email, $step1->data);
                if(isset($step1->statusCode)){
                    return redirect()->route('login')->with(['messenge'=>"Waite some minute to Login againt."]);
                }else{
                    $step3 = $this->_login_3($request->email, $request->password); 
                    if(isset($step3->statusCode)){
                        switch ($step3->message) {
                            case 'The account has not been activated, please active it before use.':
                                $regstep1 = $this->send_active_Code($request->email); // send active code for member
                                session()->put('user_reponse_2', $regstep1);
                                return redirect()->route('fontent.reg_step3')->with(['messenge'=> $regstep1->message,'data'=>$regstep1]);
                                //return redirect()->route('active')->with(['messenge'=>trim($step3->message)]);
                                break;
                            default:
                                return redirect()->route('login')->with(['messenge'=>trim($step3->message)]);
                                break;
                        }   
                    }else{
                        //print_r($step3);
                        //die('---');
                        if(isset($step3->data)){
                            $user = User::where('email',$request->email)->first();
                            // if user already found
                            if( $user ) {
                                // update the avatar and provider that might have changed
                                $user->token = $step3->data->token;
                                $user->save();
                                //$user->update([ 'token' => $step3->data->token ]);
                            } else {
                                // create a new user
                                $user = User::create([
                                    'userName'      => $step3->data->userName,
                                    'name'          => $step3->data->firstName." ".$step3->data->lastName,
                                    'email'         => $step3->data->email,
                                    'userId'        => $step3->data->userId,
                                    'firstName'     => $step3->data->firstName,
                                    'lastName'      => $step3->data->lastName,
                                    'phone'         => $step3->data->phoneNumber,
                                    'token'         => $step3->data->token,
                                    'password'      => Hash::make($request->password)
                                ]);
                            }
                            Auth::login($user, true);
                            //echo "<pre>";print_r($step3->data);echo "</pre>";
                            return redirect()->route('product.list')->with(['messenge'=>"Login Successful..."]);
                            //session()->flash('message', 'Login Successful...');                            
                        }else{
                            return redirect()->route('login')->with(['messenge'=>"System Error..Login later"]);
                        }
                    }
                }
            }else{
                return redirect()->route('login')->with(['messenge'=>trim($step1->message)]);  
            }
        }
    }
    
    public function send_request($gate, $request_param){
        //$url = 'http://13.250.104.58:8990/gateway/api/v1/ecotouch'.$gate;
        //$url = 'http://cloud.idshare.info:8081'.$gate;
        $url = 'https://id.idshare.info'.$gate;
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json','channelid'     => 'ECO-TOUCH'  ]]);
        $request_data = json_encode($request_param);
        try {
            $res = $client->request('POST', $url ,
                [
                    'headers' => [  'Content-Type'     => 'application/json','channelid'  => 'ECO-TOUCH' ],
                    'body'   => $request_data
                ]);
            $result = $res->getBody()->getContents();
            $data = json_decode($result);

        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents(); 
            $data = json_decode($responseBodyAsString); 
            /*
            $message = [
                'email'=>"true",
                'message' => 'The :attribute field is not valid.',
            ];
            return redirect('password.request')->withErrors($message);
            */
        }
        /*
        $res = $client->request('POST', $url ,
                    [
                        'headers' => [  'Content-Type'     => 'application/json','channelid'  => 'ECO-TOUCH' ],
                        'body'   => $request_data
                    ]);
        */
        echo "<pre>";  print_r($data); echo "</pre>";
        return $data;
    }
    public function send_active_Code($email){
        $url    =   '/user/security-code';
        $request_param = [
                    'email' => $email,
                    'phoneNumber' => '',
                    'securityCode' => '',
                    'receiveCodeBy' => 1,
                    'verificationType' => 1
                ]; 
        $data = $this->send_request($url, $request_param); 
        return $data;
    }
    private function _login_1($email,$password){
        $url    =   '/user/security-code';
        $request_param = [
                    'email' => $email,
                    'phoneNumber' => '',
                    'securityCode' => '',
                    'receiveCodeBy' => 1,
                    'verificationType' => 2
                ]; 
        
        $data = $this->send_request($url, $request_param);
       // echo '<script>console.log('.$data->data.');</script>';
        return $data;
    }
    private function _login_2($email,$securityCode){
        $url    =   '/user/security-code/verifying';
        $request_param = [
                    'email' =>$email,
                    'phoneNumber' => '',
                    'securityCode' => $securityCode,
                    'receiveCodeBy' => 1,
                    'verificationType' => 2
                ];
        $data = $this->send_request($url, $request_param);
        //echo '<script>console.log('.$data->data.');</script>';
        return $data;
    }
    private function _login_3( $email, $password ){
        $url    =   '/user/signIn'; 
        $request_param = [
                    'userId'=> '',
                    'email' => $email,
                    'firstName' => '',
                    'lastName'   => '',
                    'phoneNumber'   => '',
                    'payasianUserName'  => '',
                    'password'          => $password,
                    'confirmPassword'   => '',
                    'token'             => '',
                    'signInMode'        => 1
                ];
        $data = $this->send_request($url, $request_param);
        //echo '<script>console.log('.$data->data.');</script>';
        return $data;
    }

    /*
        for social login
    */
    public function redirect_social($driver){
        return Socialite::driver($driver)->redirect();
    }
    public function callback_social($driver){ 
        try {
            $user = Socialite::driver($driver)->user();
        } catch (\Exception $e) {
            return redirect()->route('login');
        }       
        $existingUser = User::where('email', $user->getEmail())->first();
        if ($existingUser) {
            auth()->login($existingUser, true);
        } else {
            $newUser                    = new User;
            $newUser->provider_name     = 'google';
            $newUser->provider_id       = $user->getId();
            $newUser->name              = $user->getName();
            $newUser->email             = $user->getEmail();
            $newUser->email_verified_at = now();
            $newUser->avatar            = $user->getAvatar();
            $newUser->save();
            auth()->login($newUser, true);
        }
        return redirect()->route('product.list')->with(['messenge'=>'Wellcome Member']);
    }

}
