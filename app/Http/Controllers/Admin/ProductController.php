<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Log;
use File;

use App\Model\Categories;
use App\Model\Products;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Products::get();
            return \Yajra\DataTables\Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('category_name', function($row){
                $category_name = '-';
                $Categories = Categories::where('id',$row->category_id)->first();
                if($Categories && isset($Categories->name)){
                    $category_name = $Categories->name;
                }
                // dd($Categories->name);
                return $category_name;
            })
            ->addColumn('img_uri', function($row){
                $row->image_uri = NULL;
                if(File::exists(public_path('images/').$row->image)){
                    $row->image_uri = env('APP_URL').'public/images/'.$row->image;
                    return '<img src="'.$row->image_uri.'" style="width:30%"/>';
                }
                return NULL;
            })
            ->addColumn('status', function($row){
                if($row->status == "0"){
                    $route = route("product.status.update",["id" => $row->id,"status"=>1]);
                    $btnStatus = '<a href="'.$route.'" class="btn-danger btn-sm">Deactive</a>';
                }else{
                    $route = route("product.status.update",["id" => $row->id,"status"=>0]);
                    $btnStatus = '<a href="'.$route.'" class="btn btn-success btn-sm">Active</a>';
                }
                return $btnStatus;
            })
            ->addColumn('action', function($row){
                $editRoute = route("product.edit",[$row->id]);
                $btn = '<a href="'.$editRoute.'" class="btn btn-primary btn-sm">Edit</a>';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-xs btn-danger btn-sm deletebutton">Delete</a>';
                return $btn;
            })
            ->rawColumns(['category_name','img_uri','status','action'])
            ->make(true);
        }
        // return Categories::get();
        return view('admin.templates.products.index');
    }

    
    public function create()
    {
        $categories = Categories::where('status','1')->get();
        return view('admin.templates.products.create',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|numeric|min:1',
            'name' => 'required|unique:products|max:255',
            'product_price' => 'required|numeric|min:0',
            'product_sku' => 'required|unique:products|max:255',
            'product_description' => 'required',
            'image' => 'required|image',
        ]);
        
        try{
            $input = $request->all();
            $input['status'] = "1";
            $input['description'] = $request->product_description;
            if($request->hasFile('image')){
                $imageName = 'product-'.$request->name.'-'.time().'.'.$request->image->extension();
                if($request->image->move(public_path('images'), $imageName)){
                    $input['image'] = $imageName;
                    if(Products::create($input)){
                        return redirect()->route('product.index')->with('success','Add Product Succcessfully');
                    }
                }
                return redirect()->back()->withErrors('Image Uploading issue')->withInputs();
            }
            return redirect()->back()->withErrors('Something want to wrong, try again')->withInputs();
        }catch(Expetion $e){
            Log::error("Add Product Error".$request->all());
        }
        return redirect()->back()->withErrors('Something want to wrong, try again')->withInputs();
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
        $edit = Products::findOrFail($id);
        if($edit->image){
            // $edit['image_uri'] = $edit;
            $edit->img_uri = NULL;
            if(File::exists(public_path('images/').$edit->image)){
                $edit->img_uri = env('APP_URL').'public/images/'.$edit->image;
            }
        }
        $categories = Categories::where('status','1')->get();
        return view('admin.templates.products.edit',compact('categories','edit'));

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|numeric|min:1',
            'name' => 'required|unique:products,name,'.$id.'|max:255',
            'product_price' => 'required|numeric|min:0',
            'product_sku' => 'required|unique:products,product_sku,'.$id.'|max:255',
            'product_description' => 'required',
            'image' => 'image',
        ]);
        
        try{
            $input = $request->all();
            $input['status'] = "1";
            $input['description'] = $request->product_description;

            $ProductsUpdate = Products::findOrFail($id);
            if($request->hasFile('image')){

                if($ProductsUpdate && File::exists(public_path('images/').$ProductsUpdate->image)){
                    unlink(public_path('images/').$ProductsUpdate->image);
                }

                $imageName = 'product-'.$request->name.'-'.time().'.'.$request->image->extension();
                if($request->image->move(public_path('images'), $imageName)){
                    $input['image'] = $imageName;
                }
                // return redirect()->back()->withErrors('Image Uploading issue')->withInputs();
            }

            if($ProductsUpdate->update($input)){
                return redirect()->route('product.index')->with('success','Update Product Succcessfully');
            }
            return redirect()->back()->withErrors('Something want to wrong, try again')->withInputs();
        }catch(Expetion $e){
            Log::error("Add Product Error".$request->all());
        }
        return redirect()->back()->withErrors('Something want to wrong, try again')->withInputs();
    }

    public function destroy($id)
    {
        $Products = Products::findOrFail($id);
        if($Products){
            if(File::exists(public_path('images/').$Products->image)){
                unlink(public_path('images/').$Products->image);
            }
            
            if($Products->delete()){
                return true;
            }
        }
        return false;
    }

    public function product_status_update($id,$update_status){
        $input['status'] = $update_status;
        $update = Products::findOrFail($id,'id');
        if($update->update($input))
        {
            return redirect()->back()->with('success','Product status update successfully');
        }
        return redirect()->back()->withErrors('Record not found');
    } 
}
