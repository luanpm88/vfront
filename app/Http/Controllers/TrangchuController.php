<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use DB;
use App\Products;
use App\Category;
use App\Feedback;

use Mail;
use App\Mail\TestEmail;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;

class TrangchuController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*
        if (session()->exists('locale') ) {
            $locale = session()->get('locale');
            App::setLocale($locale);
        }
        */
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contact(){
        return view('fontend.contact');
    }
    public function sendcontact(Request $request){
        /*
            check veryfy google use API
        */ 
        $captcha=   $request->input('g-recaptcha-response');
        $secret =   '6LfYOdAZAAAAAOIkjKWMi_vjLQCqClkYAL_gUy8E';
        $ip     =   $_SERVER['REMOTE_ADDR'];
        $site   =   "https://www.google.com/recaptcha/api/siteverify?";
        $request_param = [
            'secret'    => $secret,
            'response'  => $captcha,
            'remoteip'  => $ip            
        ];
        $response=file_get_contents( $site."secret=".$secret."&response=".$captcha."&remoteip=".$ip );
        $responseKeys = json_decode($response,true);
        
        if(intval($responseKeys['success']) == 1){
            //echo "Thực hiện gửi liên hệ";
            $contact = new Feedback();
            $contact->name= $request->name;
            $contact->email= $request->email;
            $contact->phone= $request->phone;
            $contact->desription= $request->desription;
            $contact->save();
            return redirect()->route('contact')->with(['messenge'=>"gửi liên hệ thành công",'contact_success'=>1]);
        }else{
            //echo "khong dung";
            return redirect()->route('contact')->with(['messenge'=>"vui lòng kiểm tra lại thông tin"]);
        }
    }

    public function demo(){
        return view('demo');
    }
    public function sendemail(Request $request){
        $from       = $request->from;
        $to         = $request->to;
        $subject    = $request->subject;
        $content    = $request->content;

        echo "from:".$from."<br />" ;
        echo "from:".$to."<br />" ;
        echo "subject:".$subject."<br />" ;
        echo "content:".$content."<br />" ;

        $data = [ 'message' => 'Xin chào, chúng tôi là chiến sỹ thành công rồi nha bạn nha'   ];

        Mail::to('sale@xetai.club')->send(new TestEmail($data));
        echo " gửi thành cong roi ban";

    }
    /*
        Perfect feature

        e cứ coi như e 

    */
    public function index()
    {

       //   $noidung = ['message' => 'This is a test!'];
       //   Mail::to('ketloc@gmail.com')->send(new TestEmail($noidung));
        $data   = array();
    	//$category  = Category::where(['home'=>1,'parent'=>)->get(); 
        $category  = DB::table('category')->where('home', '=', 1)->where('parent', '<', 1)->limit(7)->get();
        foreach ($category as $cat) {
            $products  = Products::where('category_id',$cat->id)->get();
            $child_cate  = DB::table('category')->where('parent', '=', $cat->id)->limit(4)->get();
            $data[] = array( 'category'=>$cat, 'data'=> $products,'child' => $child_cate );
        }
        /*
            bai viet hot
        */
        $products_hot = DB::table('products')
                            ->join('category', 'products.category_id', '=', 'category.id')
                            ->select( 'products.*', 'category.title as cat_title'
                        )->where('hot', 1 )->orderByRaw('view DESC')->limit(16)->get();
        /*
        $hot   =   '1';
        $category_hot = Category::whereIn("id", function ($query) use ($hot) {
                    $query->select("id")
                        ->from((new Products)->getTable())
                        ->where("hot", $hot);
                })->get();
        */
        $category_hot   =   array();
        foreach ($products_hot as $value) {
             $category_hot[  $value->category_id ]     =    $value->cat_title;
        }
  
        return view('fontend.home', [
                //'data'=>$data, 
                'category'      =>$category,
                'products_hot'  =>$products_hot,
                'category_hot'  =>$category_hot
                //'popular'=>$post_popular,
        ]);
    }
    public function dashboard(){
        return view('backend.dashboard'); 
    }

     
    //---------end class
}
