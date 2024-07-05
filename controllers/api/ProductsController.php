<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;
use Validator;

use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Helpers\Helpers;
use WANdisco\Wandisco\Models\Product;
class ProductsController extends Controller
{
    protected $Product;

    protected $helpers;

    public function __construct(Product $Product, Helpers $helpers)
    {
        parent::__construct();
        $this->Product    = $Product;
        $this->helpers          = $helpers;
    }


    public function index(){
        $data = $this->Product->with(array(
            'documentations'=>function($query){
                $query->select();
            }, ))->select()->get()->toArray();
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }


    public function show($id){
        $data = $this->Product->with(array(
            'documentations'=>function($query){
                $query->select();
            }, ))->select()->where('id', '=', $id)->first();
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function store(Request $request){

        $arr = $request->all();

        while ( $data = current($arr)) {
            $this->Product->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->Product->rules);

        if( $validation->passes() ){
            $this->Product->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Product->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        $status = $this->Product->where('id',$id)->update($request->all());

        if( $status ){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->Product->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->Product->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public static function getAfterFilters()
    {
        return [];
    }

    public static function getBeforeFilters()
    {
        return [];
    }

    public static function getMiddleware()
    {
        return [];
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        return $this->{$method}(...array_values($parameters));
    }

}
