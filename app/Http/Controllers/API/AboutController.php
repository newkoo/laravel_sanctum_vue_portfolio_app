<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class AboutController extends Controller
{
    public function edit_about()
    {
        return About::orderBy('id', 'desc')->first();
    }

    public function update_about(Request $request, $id)
    {
        $about = About::find($id);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required'
        ]);

        if ($about->photo != $request->photo) {
            $strpos = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $strpos);
            $ex = explode('/', $sub)[1];
            $name = time() . '.' . $ex;
            $img = Image::make($request->photo)->resize(700, 500);
            $uploadPath = public_path() . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR;
            $image = $uploadPath . $about->photo;
            $img->save($uploadPath . $name);
            if (file_exists($image))
                @unlink($image);
            else
                $name = $about->photo;

            $about->photo = $name;
        }

        if ($about->cv != $request->cv) {
            $strpos = strpos($request->cv, ';');
            $sub = substr($request->cv, 0, $strpos);
            $ex = explode('/', $sub)[1];
            $namecv = time() . '.' . $ex;
            $img = Image::make($request->cv)->resize(700, 500);
            $uploadPath = public_path() . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR;
            $image = $uploadPath . $about->cv;
            $img->save($uploadPath . $namecv);
            if (file_exists($image))
                @unlink($image);
            else
                $namecv = $about->cv;

            $about->cv = $namecv;
        }

        $about->name = $request->name;
        $about->email = $request->email;
        $about->phone = $request->phone;
        $about->address = $request->address;
        $about->description = $request->description;
        $about->tagline = $request->tagline;

        $about->save();
    }
}
