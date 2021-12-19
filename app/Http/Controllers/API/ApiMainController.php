<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;

use File;
use Validator;
use App\Model\Categories;
use App\Model\Products;

class ApiMainController extends BaseController
{
    public $current_date;
    function __construct(){
    }
    
    public function add_category(Request $request){ 
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories|max:255',
            'image' => 'required|image',
            ]
        );
        
        if($validator->fails()){
            return $this->sendError($validator->errors()->first());
        }
        
        $input = $request->all();
        $input['status'] = "1";
        if($request->hasFile('image')){
            $imageName = $request->name.'-'.time().'.'.$request->image->extension();
            if($request->image->move(public_path('images'), $imageName)){
                $input['image'] = $imageName;
                $Categories = Categories::create($input);
                if($Categories){
                    $success = $Categories->id;
                    return $this->sendResponse($success, 'Category add successfully.');
                }
            }
            return $this->sendError('Image Uploading issue');
        }
        
        return $this->sendError('Error message'); 
    }
    
    public function edit_category(Request $request){ 
        
        $validator = Validator::make($request->all(), [
            'edit_id' => 'required',
            'name' => 'required|unique:categories,name,'.$request->edit_id.'|max:255',
            'image' => 'image',
            ]
        );
        
        if($validator->fails()){
            return $this->sendError($validator->errors()->first());
        }
        
        $input = $request->all();
        $CategoryUpdate = Categories::findOrFail($request->edit_id);
        if($request->hasFile('image')){
            
            if($CategoryUpdate && File::exists(public_path('images/').$CategoryUpdate->image)){
                unlink(public_path('images/').$CategoryUpdate->image);
            }
            
            $imageName = $request->name.'-'.time().'.'.$request->image->extension();
            if($request->image->move(public_path('images'), $imageName)){
                $input['image'] = $imageName;
            }
        }
        
        if($CategoryUpdate->update($input)){
            $success = $CategoryUpdate->id;
            return $this->sendResponse($success, 'Category update successfully.');
        }        
        return $this->sendError('Error message'); 
    }
    
    public function get_category_list(Request $request){ 
        
        $success = [];
        $data = Categories::where('status','1');
        if(isset($request->id)){
            $data = $data->where('id',$request->id);
        }
        $data = $data->get();
        if($data){
            $data = $data->map(function($record){
                $record->image_uri = "";
                if(File::exists(public_path('images/').$record->image)){
                    $record->image_uri = env('APP_URL').'public/images/'.$record->image;
                }
                return $record;
            });
            
            $success = $data;
            
            return $this->sendResponse($success, 'Category List');
            
        }
        return $this->sendResponse($success, 'Category not found');
    }
    
    public function add_product(Request $request){ 
        
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric|min:1',
            'name' => 'required|unique:products|max:255',
            'product_price' => 'required|numeric|min:0',
            'product_sku' => 'required|unique:products|max:255',
            'product_description' => 'required',
            'image' => 'required|image',
            ]
        );
        
        if($validator->fails()){
            return $this->sendError($validator->errors()->first());
        }
        $input = $request->all();
        $input['status'] = "1";
        $input['description'] = $request->product_description;
        if($request->hasFile('image')){
            $imageName = 'product-'.$request->name.'-'.time().'.'.$request->image->extension();
            if($request->image->move(public_path('images'), $imageName)){
                $input['image'] = $imageName;
                $Products = Products::create($input);
                if($Products){
                    $success = $Products->id;
                    return $this->sendResponse($success, 'Product add successfully.');
                }
            }
            return $this->sendError('Image Uploading issue');
        }
        return $this->sendError('Something want to wrong try again'); 
    }
    
    public function edit_product(Request $request){ 
        
        $validator = Validator::make($request->all(), [
            'edit_id' => 'required',
            'category_id' => 'required|numeric|min:1',
            'name' => 'required|unique:products,name,'.$request->edit_id.'|max:255',
            'product_price' => 'required|numeric|min:0',
            'product_sku' => 'required|unique:products,product_sku,'.$request->edit_id.'|max:255',
            'product_description' => 'required',
            'image' => 'image',
            ]
        );
        
        if($validator->fails()){
            return $this->sendError($validator->errors()->first());
        }
        
        $input = $request->all();
        $input['status'] = "1";
        $input['description'] = $request->product_description;
        
        $ProductsUpdate = Products::findOrFail($request->edit_id);
        if($request->hasFile('image')){
            
            if($ProductsUpdate && File::exists(public_path('images/').$ProductsUpdate->image)){
                unlink(public_path('images/').$ProductsUpdate->image);
            }
            
            $imageName = 'product-'.$request->name.'-'.time().'.'.$request->image->extension();
            if($request->image->move(public_path('images'), $imageName)){
                $input['image'] = $imageName;
            }
        }
        
        if($ProductsUpdate->update($input)){
            $success = $ProductsUpdate->id;
            return $this->sendResponse($success, 'Product update successfully.');
        }
        
        return $this->sendError('Something want to wrong try again'); 
    }
    
    public function get_product_list(Request $request){ 
        
        $success = [];
        $data = Products::where('status','1');
        
        if(isset($request->id)){
            $data = $data->where('id',$request->id);
        }
        $data = $data->get();
        if($data){
            $data = $data->map(function($record){
                $record->category_name = "";
                $Categories = Categories::where('id',$record->category_id)->first();
                if($Categories && isset($Categories->name)){
                    $record->category_name = $Categories->name;
                }
                
                $record->image_uri = "";
                if(File::exists(public_path('images/').$record->image)){
                    $record->image_uri = env('APP_URL').'public/images/'.$record->image;
                }
                return $record;
            });
            
            $success = $data;
            
            return $this->sendResponse($success, 'Product List');
            
        }
        return $this->sendResponse($success, 'Product not found');
    }
}