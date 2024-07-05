<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use WANdisco\Wandisco\Models\News;
use System\Models\File;

class WANdiscoNewsAPIController extends Controller
{
	protected $News;
    protected $helpers;

    public function __construct(News $News, Helpers $helpers)
    {
        parent::__construct();
        $this->News    = $News;
        $this->helpers          = $helpers;
    }

    public function index(){

        //$data = $this->News->all();
        $this->from_date = "2018-01-01"; 
        $this->to_date = date("Y-m-d");
        //$data = $this->News::where('deleted_at', null)->whereBetween('created_at', array($this->from_date, $this->to_date))->take(1000)->orderBy('created_at', 'desc')->get();
        $data = $this->News::where('deleted_at', null)->take(1000)->orderBy('created_at', 'desc')->get();
        $data->toArray();

        //enrich with image data - slowly but api/v1/news isn't pickup up the one to many relationship and i need this working now. #FIXME
        foreach($data as $idx=>$item){ 
                $images = \System\Models\File::where('attachment_id', $item['id']) ->take(10)->get();
                foreach($images as $image){ 
                    $imageUrl =  $image->getPath();
                    $data[$idx]['images'][] = $imageUrl;  
                }

           }

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function show($id){

        $data = $this->News->where('id',$id)->first();


        if( count($data) > 0){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }

    public function store(Request $request){
        return false; 

    	$arr = $request->all();

        while ( $data = current($arr)) {
            $this->News->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->News->rules);
        
        if( $validation->passes() ){
            $this->News->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->News->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        return false; 
        $status = $this->News->where('id',$id)->update($data);
    
        if( $status ){
            
            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        return false; 
        $this->News->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        return false; 
        $this->News->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }


    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }
    
}
