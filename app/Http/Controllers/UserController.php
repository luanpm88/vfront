<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $hidden = [
        'provider_name', 'provider_id', 'password', 'remember_token',
    ];
    
    public function index()
    {
        $users = User::paginate(10); 
        return view('backend.Users.list', ['data'=> $users] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.Users.add' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = isset($request->phone)? $request->phone:  '';
        $user->userId       = isset($request->userId)? $request->userId: '';
        $user->firstName    = isset($request->firstName)? $request->firstName: '';
        $user->lastName     = isset($request->lastName)? $request->lastName: '';
        $user->is_admin     = isset($request->is_admin)? $request->is_admin: 0;
        if(isset($request->password))   $user->password     = Hash::make($request->password); // change password
        if($request->hasFile('avatar')){
            $filename = $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->move(
                base_path() . '/public/upload/Avatar/', $filename
            );
        }
        $user->save();
        return redirect('backend.listuser')->with(['message'=> 'Successfully Create User!!']);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);  
        return view('backend.Users.edit',['data'=>$user]);
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
        $user = User::find($id);
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->userId       = $request->userId;
        $user->firstName    = $request->firstName;
        $user->lastName     = $request->lastName;
        $user->is_admin     = isset($request->is_admin)? $request->is_admin: 0;
        if(isset($request->password)){
            if($request->password != $user->password){
                $user->password   = Hash::make($request->password);
            }            
        }
        if($request->hasFile('avatar')){
            $filename = $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->move(
                base_path() . '/public/upload/Avatar/', $filename
            );
            $user->avatar= $filename;
        }
        $user->save();
        return redirect()->route('backend.listuser')->with(['message'=> 'Successfully Updated User!!']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete(); 
        return redirect()->route('backend.listuser')->with(['message'=> 'Successfully deleted!!']);
    }
    public function profile(Request $request){
        if (Auth::check()) { 
            $user = Auth::user();
            return view('fontend.user.profile',['data'=>$user]);
        }
    }
    public function update_profile(Request $request)
    {
        if (Auth::check()) { 
            $user = Auth::user();
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->phone        = $request->phone;
            $user->userId       = $request->userId;
            $user->firstName    = $request->firstName;
            $user->lastName     = $request->lastName;
            if(isset($request->password)){
                if($request->password != $user->password){
                    $user->password   = Hash::make($request->password);
                }            
            }
            if($request->hasFile('avatar')){
                $filename = $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move(
                    base_path() . '/public/upload/Avatar/', $filename
                );
                $user->avatar= $filename;
            }
            $user->save();
            return redirect()->route('fontent.profile')->with(['message'=> 'Successfully Updated User!!']);
        }
    }
    public function get_exchange_rate($from, $to){
        $client     = new Client();
        $result     =   $client->request('GET','http://13.250.104.58:8990/gateway/api/v1/ecobank/exchanging/rate/USDex/NEE');
        //echo "<pre>"; print_r($result); echo "</pre>";
    }
    public function get_wallet(){
        $client     =   new Client();
        $result     =   $client->request('GET','http://13.250.104.58:8082/currency/wallet');
        $data       =   json_decode($result->getBody()->getContents());
        //echo "<pre>"; print_r($data); echo "</pre>";
    }
    public function sendactive($request){
        $credential =   $request->except(['_token']);
        $request->validate( [  'email'      =>'require'  ]);
        $step1 = $this->send_active_Code($request->email);
        if($step1->statusCode){
            return view('fontend.user.active')->with(['messenge'=>$step1->messenge]);
        }else{
            return view('fontend.user.active')->with(['messenge'=>"Code Active has send to your email, Pls Check and active your Accout."]);
        }        
    }
    public function doactive($request){
        $url    = '/security-code/verifying';   
        $credential =   $request->except(['_token']);
        $request->validate(['email'      =>'require' ,'code' =>'require'] );
        $url    =   '/user/security-code';
        $request_param = [
                    'email' => "'".$requestemail."'",
                    'phoneNumber' => '',
                    'securityCode' => "'".$request->code."'",
                    'receiveCodeBy' => 1,
                    'verificationType' => 1
                ];
        $step1 = $this->send_request($url, $request_param);
        if($step1->statusCode){
            return view('fontend.user.active')->with(['messenge'=>$step1->messenge]);
        }else{
            return redirect()->route('login')->with(['messenge'=>$step1->messenge]);
        }
    }
    public function dologin(request $request, $next){ 
        $request->validate([
            'email'      =>'require',
            'password'  =>'require']
        );
        $credential =   $request->except(['_token']);
        /*
            check status account
        */
        $step1      =   $this->_login_1($request->email,$request->password);
        if($step1->statusCode){ 
            return redirect()->route('login')->with(['messenge'=>trim($step1->message)]);
        }else{
            if($step1->result==1){
                $step2 = $this->_login_2($request->email, $step1->data);
                if($step1->statusCode){
                    return redirect()->route('login')->with(['messenge'=>"Waite some minute to Login againt."]);
                }else{
                    $step3 = $this->_login_3($request->email, $request->password);
                    if($step3->statusCode){
                            switch ($step3->message) {
                                case 'The account has not been activated, please active it before use.':
                                    $regstep1 = $this->send_active_Code(); // send active code for member
                                    return redirect()->route('active')->with(['messenge'=>trim($step3->message)]);
                                    break;
                                default:
                                    return redirect()->route('login')->with(['messenge'=>trim($step3->message)]);
                                    break;
                            }   
                    }else{
                        if($step3->data){
                            $user = User::where('email',$request->email)->first();                            
                            // if user already found
                            if( $user ) {
                                // update the avatar and provider that might have changed
                                $user->update([ 'token' => $step3->data->token ]);
                            } else {
                                // create a new user
                                $user = User::create([
                                    'name'          => $step3->data->userName,
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
                            return redirect()->route('profile')->with(['messenge'=>"Login Successful..."]);
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
                                'headers' => [  'Accept'     => 'application/json','channelid'  => 'ECO-TOUCH' ],
                                'body'   => $request_data
                            ]); //  $statuscode = $res->getStatusCode();
                $result = $res->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\ClientException $e) {//echo 'Caught response: ' . $e->getResponse()->getStatusCode();
            $result = $e->getResponse()->getBody();
        }
        $data = json_decode($result);
        return $data;
    }
    public function send_active_Code($email){
        $url    =   '/user/security-code';
        $request_param = [
                    'email' => "'".$email."'",
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
                    'email' => "'".$email."'",
                    'phoneNumber' => '',
                    'securityCode' => '',
                    'receiveCodeBy' => 1,
                    'verificationType' => 2
                ]; 
        $data = $this->send_request($url, $request_param);
        return $data;
    }
    private function _login_2($email,$securityCode){
        $url    =   '/user/security-code/verifying';
        $request_param = [
                    'email' => "'".$email."'",
                    'phoneNumber' => '',
                    'securityCode' => $securityCode,
                    'receiveCodeBy' => 1,
                    'verificationType' => 2
                ];
        $data = $this->send_request($url, $request_param);
        return $data;
    }
    private function _login_3( $email, $password ){
        $url    =   '/user/signIn'; 
        $request_param = [
                    'userId'=> '',
                    'email' => "'".$email."'",
                    'firstName' => '',
                    'lastName'   => '',
                    'phoneNumber'   => '',
                    'payasianUserName'  => '',
                    'password'          => "'".$password."'",
                    'confirmPassword'   => '',
                    'token'             => '',
                    'signInMode'        => 1
                ];
        $data = $this->send_request($url, $request_param);
        echo '<script>console.log('.$data->data.');</script>';
        return $data;
    }
    /*
        registration
    */
    public function register_show_1()
    {
        return view('fontend.user.register');
    }
    public function _register_1(request $request){
        /*
            check validate infomation
        */
        $url    =   '/user/registration';
        $request_param = [
                    'userId'    => "",
                    'email'             => $request->email,
                    'firstName'         => $request->name,
                    'lastName'          => $request->lastname,
                    'phoneNumber'       => $request->phone,
                    'password'          => $request->password,
                    'confirmPassword'   => $request->passwordconfirm,
                    'token'     =>''
                ];
        session()->put('profile', $request_param);
        $data = $this->send_request($url, $request_param);
        if($data){
            if(isset($data->result)){
                if($data->result==1){
                    session()->put('user_reponse_1', $data->data);
                    return redirect()->route('fontent.reg_step2')->with(['messenge'=> $data->message,'data'=>$data]);
                }
            }else{
                echo "<pre>";print_r($data);
                echo "statusCode:".$data->statusCode;
                if(isset($data->statusCode)){

                    return redirect()->route('fontent.reg_step1')->with(['messenge'=> $data->statusCode]);
                }
            }
        }
    }
    /*
        get security code
    */
    public function register_show_2()
    {
        return view('fontend.user.verify');
    }
    public function _register_2(request $request){
        $url    =   '/user/security-code';
        $profile= (object)session()->get('profile'); 
        $request_param = [
                    'email' => $profile->email,
                    'phoneNumber' => '',
                    'securityCode' => '',
                    'receiveCodeBy' => 1,
                    'verificationType' => 1
                ]; 
        $data = $this->send_request($url, $request_param);
        if($data){
            if(isset($data->result)){
                if($data->result=='true'){
                    session()->put('user_reponse_2', $data);
                    return redirect()->route('fontent.reg_step3')->with(['messenge'=> $data->message,'data'=>$data]);
                }
            }elseif(isset($data->statusCode)){
                return redirect()->route('fontent.reg_step2')->with(['messenge'=> $data->message]);
            }
        }
        return $data;
    }
    /*
        verifying
    */
    public function register_show_3()
    {
        $reponse_2  =   (object)session()->get('user_reponse_2');
        echo "Reponse_2 catch:";print_r($reponse_2);
        return view('fontend.user.active');
    }
    /*
        get code form email to verify
    */
    public function _register_3(Request $request){
        $url    =   '/user/security-code/verifying';
        $user_code  =    $request->code;
        $profile    =   (object)session()->get('profile');
        $reponse_2  =   (object)session()->get('user_reponse_2');
        if($user_code==$reponse_2->data){
            $request_param = [
                        'email' => $profile->email,
                        'phoneNumber' => '',
                        'securityCode' => $user_code,
                        'receiveCodeBy' => 1,
                        'verificationType' => 1
                    ]; 
            $data = $this->send_request($url, $request_param);
            if($data){
                if(isset($data->result)){
                    if($data->result=='true'){
                        session()->put('user_reponse_3', $data);
                        return redirect()->route('fontent.reg_step4')->with(['messenge'=> $data->message,'data'=>$data]);
                    }               
                }elseif(isset($data->statusCode)){
                    if($data->message=='The security code was expired.'){
                        return redirect()->route('fontent.reg_step3')->with(['messenge'=> $data->message,'send_againt'=>1]); 
                    }else{
                        return redirect()->route('fontent.reg_step3')->with(['messenge'=> $data->message]);
                    }
                }else{
                    return redirect()->route('fontent.reg_step3')->with(['messenge'=> $data->message]);
                } 
            }else{
                return redirect()->route('fontent.reg_step3')->with(['messenge'=> "it seem is not correct, Pls check email againt"]);
            }
        }
    }
    /*
        comform register and comfirm password
    */
    public function register_show_4()
    {
        return view('fontend.user.comfim');
    }
    public function register_show_4_password_againt()
    {
        return view('fontend.user.comfim_pasword');
    }
    public function _register_4(Request $request){
        $url    =   '/user/registration/password-confirmation';
        $profile    =   (object)session()->get('profile');
        $reponse_3  =   (object)session()->get('user_reponse_3');
        $password   =   '';
        $email      =   '';
        $comfim_pasword = '';
        $phoneNumber    = "";
        $firstName      = "";
        $lastName       = "";
        if(isset($profile->confirmPassword)){
            if(isset($profile->confirmPassword)){
                $password           =   $profile->password;
                $comfim_pasword     =   $profile->confirmPassword;
            }
        }elseif(isset($request->passwordconfirm)){
            $password            =   $request->password;
            $comfim_pasword     =   $request->passwordconfirm;
        }
        if($reponse_3){
            $email = $reponse_3->data->email;
            $phoneNumber   = $reponse_3->data->phoneNumber;
            $firstName    = $reponse_3->data->firstName;
            $lastName    = $reponse_3->data->lastName;
        }elseif(isset($profile)){
            $email = $profile->email;
        }
        if( $password !='' & $email != ''){
            if($comfim_pasword == $password){
                $request_param = [
                            "payasianUserName" => "",
                            "password"      => $password,
                            "confirmPassword" => $comfim_pasword,
                            "pinCode"       => "",
                            "signInMode"    => 1,
                            "token"         => "",
                            "userId"        => "",
                            "email"             => $email,
                            'phoneNumber'       => $phoneNumber,
                            'firstName'         => $firstName,
                            'lastName'          => $lastName,
                            "idCardNumber"      => "",
                            "passportNumber"    =>""
                        ];
                $data = $this->send_request($url, $request_param);
                if($data){
                    if(isset($data->result)){
                        if($data->result=='1'){
                            session()->put('user_reponse_4', $data);
                            return redirect()->route('fontent.reg_succ')->with(['messenge'=> $data->message,'data'=>$data]);
                        }
                    }elseif(isset($data->statusCode)){
                        if($data->message=='The security code was expired.'){
                            return redirect()->route('fontent.reg_resesend_code')->with(['messenge'=> $data->message,'send_againt'=>1]); 
                        }elseif($data->message=='Verify security code before confirm password.'){                            
                            return redirect()->route('fontent.reg_resesend_code')->with(['messenge'=> $data->message,'send_againt'=>1]);
                        }else{
                            return redirect()->route('fontent.reg_step3')->with(['messenge'=> $data->message,'send_againt'=>1]);
                        }
                    }else{
                        return redirect()->route('fontent.reg_step3')->with(['messenge'=> "Chờ ít phút nhé bạn"]);
                    }
                }else{
                    return redirect()->route('fontent.reg_step3')->with(['messenge'=> "it seem is not correct, Pls check email againt"]);
                }
            }else{ // password không giống nhau
                return redirect()->route('fontent.reg_step4_confirm_password')->with(['messenge'=> "Mật khẩu không giống nhau"]);
            }
        }
        return redirect()->route('fontent.reg_step4_confirm_password')->with(['messenge'=> "Vui lòng nhập lại mật khẩu"]);
    }
    public function _register_successful(){
        return view ('fontend.user.reg_success');
    }
    public function resend_show()
    {
        return view('fontend.user.resendcode');
    }
    public function resend_code(request $request){
        $url    =   '/user/security-code';
        $profile = (object)session()->get('profile'); 
        $request_param = [
                    'email' => $request->email,
                    'phoneNumber' => '',
                    'securityCode' => '',
                    'receiveCodeBy' => 1,
                    'verificationType' => 1
                ]; 
        $data = $this->send_request($url, $request_param);
        if($data){
            if($data->result=='true'){
                session()->put('user_reponse_2', $data);
                return redirect()->route('fontent.reg_step3')->with(['messenge'=> $data->message,'data'=>$data]);
            }
        }
        return redirect()->back();
    }
    public function forget_password(){
        return view('fontend.user.email');
    }
}