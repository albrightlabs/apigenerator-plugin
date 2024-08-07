<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use WANdisco\Wandisco\Models\ResourceType;
class WANdiscoResourceTypesController extends Controller
{
	protected $ResourceType;

    protected $helpers;

    public function __construct(ResourceType $ResourceType, Helpers $helpers)
    {
        parent::__construct();
        $this->ResourceType    = $ResourceType;
        $this->helpers          = $helpers;
    }

    public function index(){

        $data = $this->ResourceType->all()->where('resource.deleted_at', '=', null)->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function show($id){

        $data = $this->ResourceType->where('id',$id)->first();

        if( count($data) > 0){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }

    public function store(Request $request){

        return false; 
    	$arr = $request->all();

        while ( $data = current($arr)) {
            $this->ResourceType->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->ResourceType->rules);

        if( $validation->passes() ){
            $this->ResourceType->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->ResourceType->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        return false; 
        $status = $this->ResourceType->where('id',$id)->update($data);

        if( $status ){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        return false; 
        $this->ResourceType->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        return false; 
        $this->ResourceType->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }


    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }

}
