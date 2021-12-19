<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Log;
use File;

use App\Model\Categories;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Categories::latest()->get();
            return \Yajra\DataTables\Datatables::of($data)
            ->addIndexColumn()
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
                    $route = route("category.status.update",["id" => $row->id,"status"=>1]);
                    $btnStatus = '<a href="'.$route.'" class="btn-danger btn-sm">Deactive</a>';
                }else{
                    $route = route("category.status.update",["id" => $row->id,"status"=>0]);
                    $btnStatus = '<a href="'.$route.'" class="btn btn-success btn-sm">Active</a>';
                }
                return $btnStatus;
            })
            ->addColumn('action', function($row){
                $editRoute = route("category.edit",[$row->id]);
                $btn = '<a href="'.$editRoute.'" class="btn btn-primary btn-sm">Edit</a>';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-xs btn-danger btn-sm deletebutton">Delete</a>';
                return $btn;
            })
            ->rawColumns(['img_uri','status','action'])
            ->make(true);
        }
        // return Categories::get();
        return view('admin.templates.category.index');
    }
    
    public function create()
    {
        return view('admin.templates.category.create');
    }
    
    
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'image' => 'required|image',
        ]);
        
        try{
            $input = $request->all();
            $input['status'] = "1";
            if($request->hasFile('image')){
                $imageName = $request->name.'-'.time().'.'.$request->image->extension();
                if($request->image->move(public_path('images'), $imageName)){
                    $input['image'] = $imageName;
                    if(Categories::create($input)){
                        return redirect()->route('category.index')->with('success','Add Category Succcessfully');
                    }
                }
                return redirect()->back()->withErrors('Image Uploading issue')->withInputs();
            }
            return redirect()->back()->withErrors('Something want to wrong, try again')->withInputs();
        }catch(Expetion $e){
            Log::error("Add Category Error".$request->all());
        }
        return redirect()->back()->withErrors('Something want to wrong, try again')->withInputs();
    }
    
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
        
        $edit = Categories::findOrFail($id);
        if($edit->image){
            // $edit['image_uri'] = $edit;
            $edit->img_uri = NULL;
            if(File::exists(public_path('images/').$edit->image)){
                $edit->img_uri = env('APP_URL').'public/images/'.$edit->image;
            }
        }
        // return $edit;
        return view('admin.templates.category.edit',compact('edit'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,'.$id.'|max:255',
            'image' => 'nullable|image',
        ]);
        
        try{
            $input = $request->all();
            $CategoryUpdate = Categories::findOrFail($id);
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
                return redirect()->route('category.index')->with('success','Update Category Succcessfully');
            }
            return redirect()->back()->withErrors('Something want to wrong, try again')->withInputs();
        }catch(Expetion $e){
            Log::error("Update Category Error".$request->all());
        }
        return redirect()->back()->withErrors('Something want to wrong, try again')->withInputs();
    }
    
    public function destroy($id)
    {
        $Categories = Categories::findOrFail($id);
        // dd($Categories,File::exists(public_path('images/').$Categories->image),public_path('images/').$Categories->image);
        if($Categories){
            if(File::exists(public_path('images/').$Categories->image)){
                unlink(public_path('images/').$Categories->image);
            }
            
            if($Categories->delete()){
                return true;
            }
        }
        return false;
        // return redirect()->route('category.index')->withErros('Something want to wrong');
    }
    
    public function category_status_update($id,$update_status){
        $input['status'] = $update_status;
        $update = Categories::findOrFail($id,'id');
        if($update->update($input))
        {
            return redirect()->back()->with('success','Category status update successfully');
        }
        return redirect()->back()->withErrors('Record not found');
    } 
}
