<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Models;

class BannerController extends Controller
{
    public function Banner(){
        $banners = Models\Banner::all();
        return response()->view('pages.banner', [
            'banners' => $banners,
        ]);
    }

    public function BannerCreate(Request $request){
        try{
            $bannerForm = $request->validate([
                'name' => 'required|string|unique:banners,name',
                'files' => 'required|file|max:10240'
            ]);
            $url = "";
            if($request->only("files")){
                $url = Storage::url($request->file('files')->store('public/banner'));
            }
            Models\Banner::create([
                'name' => $bannerForm['name'],
                'path' => $url,
            ]);
            return back()->with('success', "success create data");
        }catch(ValidationException $e){
            return back()->withErrors($e->validator->errors());
        }
    }

    public function BannerDelete(Request $request, $id){
        try{
            $banner = Models\Banner::find($id);
            if(!$banner){
                return back()->withErrors([
                    'title' => 'Action Delete',
                    'messages' => "Banner Doesn't exist",
                ]);
            }
            if(file_exists(storage_path("app/public".str_replace("/storage", "", $banner->path)))){
                unlink(storage_path("app/public".str_replace("/storage", "", $banner->path)));
            }
            //dd("app/public$banner->path");
            $banner->delete();
            return back()->with('success', "success create data");
        }catch(\Exception $e){
            return back()->withErrors($e);
        }
    }

   public function BannerEdit(Request $request, $id){
        try{
            $dataBanner = $request->validate([
                'name' => 'nullable|string',
                'files' => 'nullable|file|max:10240|mimes:jpeg,png,jpg'
            ]);
            $banner = Models\Banner::find($id);
            if(!$banner){
                back()->withErrors([
                    'messages' => 'Error on Edit Banner',
                ]);
            }
            if($request->only('files')){
                if(file_exists(storage_path("app/public".str_replace("/storage", "", $banner->path)))){
                    unlink(storage_path("app/public".str_replace("/storage", "", $banner->path)));
                }
                $dataBanner['path'] = Storage::url($request->file("files")->store("public"));
            }
            $banner->update($dataBanner);
            return back()->with('success', "Success edit banner");
        }catch(ValidationException $e){
            return back()->withErrors($e->validator->errors());
        }
    }
}
