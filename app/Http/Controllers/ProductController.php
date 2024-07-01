<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Models;

class ProductController extends Controller
{
    public function Product(Request $request){
        
        $products = Models\Product::where('users_id', $request->user()->id)->get();

        return response()->view("pages.product", [
            'products' => $products,
        ]);
    }

    public function ProductCreate(Request $request){
        try{
            $dataProduct = $request->validate([
                'name' => 'required|string|unique:product,id',
                'description' => 'required|string',
                'description_details' => 'required|string',
                'users_id' => 'nullable|numeric|exist:users,id',
                'price' => 'required|numeric',
                'min_order' => 'required|numeric',
                'duration' => 'required|string',
                'location' => 'required|string',
                'is_event' => 'nullable|string',
                'is_package' => 'nullable|string',
            ]);

            if(!$request->only('description_details')){
                $dataProduct['description_details'] = " ";
            }

            if($request->only('is_event')){
                $dataProduct['is_event'] = true;
            }

            if($request->only('is_package')){
                $dataProduct['is_package'] = true;
            }

            if(!$request->only('users_id')){
                $dataProduct['users_id'] = $request->user()->id;
            }

            $product = Models\Product::create($dataProduct);

            return back()->with('success', 'success create new product');
        }catch(ValidationException $e){
            return back()->withInput()->withErrors($e->validator->errors());
        }
    }

    public function ProductAddImage(Request $request, $id){
        try{
            $request->validate([
                'files[]' => 'nullable|file|max:10240|mimes:jpeg,png,jpg'
            ]);

            if($request->hasFile('files')){
                $files = $request->file('files');
                foreach($files as $file){
                    $path = Storage::disk('public')->putFile('product', $file);
                    $product = Models\Product::find($request->id);
                    if(!$product){
                        return back()->with('error', 'product not found');
                    }
                    Models\Images::create([
                        'name' => $product->name.' - '.$file->getClientOriginalName(),
                        'path' => $path,
                        'product_id' => $id,
                    ]);
                }
            }

            return back()->with('success', 'success add image');
        }catch(ValidationException $e){
            return back()->withErrors($e->validator->errors());   
        }
    }

    public function ProductDeleteImage(Request $request, $id){
        try{
            $images = Models\Images::find($id);
            // delete images
            if(file_exists(storage_path("app/public".str_replace("/storage", "", $images->path)))){
                unlink(storage_path("app/public".str_replace("/storage", "", $images->path)));
            }
            $images->delete();
            return back()->with('success', 'Product image berhasil di hapus.');
        }catch(\Exception $e){
            return back()->withErrors([
            
            ]);
        }
    }

    public function ProductToggleEvent(Request $request, $id){
        try{

            $product = Models\Product::find($id);
            $product->is_event = !$product->is_event;
            $product->save();
            
            return back()->with('success', 'success toggle product');
        }catch(\Exception $e){
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function ProductTogglePackage(Request $request, $id){
        try{

            $product = Models\Product::find($id);
            $product->is_package = !$product->is_package;
            $product->save();
            
            return back()->with('success', 'success toggle product');
        }catch(\Exception $e){
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function ProductEdit(Request $request, $id){
        try{
            $dataProduct = $request->validate([
                'name' => 'required|string|unique:product,id',
                'description' => 'required|string',
                'description_details' => 'required|string',
                'users_id' => 'nullable|numeric|exist:users,id',
                'price' => 'required|numeric',
                'min_order' => 'required|numeric',
                'duration' => 'required|string',
                'location' => 'required|string',
                'is_event' => 'nullable|string',
                'is_package' => 'nullable|string',
            ]);

            if(!$request->only('description_details')){
                $dataProduct['description_details'] = " ";
            }

            if($request->only('is_event')){
                $dataProduct['is_event'] = true;
            }

            if($request->only('is_package')){
                $dataProduct['is_package'] = true;
            }

            if(!$request->only('users_id')){
                $dataProduct['users_id'] = $request->user()->id;
            }

            $product = Models\Product::find($id);

            $product->update($dataProduct);

            return back()->with('success', 'success edit product');
        }catch(ValidationException $e){
            return back()->withInput()->withErrors($e->validator->errors());
        }
    }

    public function ProductDelete(Request $request, $id){
        try{
            $product = Models\Product::find($id);
            
            if(!$product){
                return back()->with('error', 'product not found');
            }

            $product->delete();
            return back()->with('success', 'success delete product');
        }catch(\Exception $e){
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }
}
