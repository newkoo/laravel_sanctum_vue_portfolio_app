<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Lib\DatatableResponder;
use App\Models\Project;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProjectController extends Controller
{
    public function get_all_project()
    {
        $projects = Project::orderBy('id', 'DESC')->get();
        return DatatableResponder::sendResponse($projects, $projects->count());
    }
    public function get_project($id)
    {
        return Project::find($id);
    }

    public function create_project(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $project = new Project();
        $project->title = $request->title;
        $project->description = $request->description;
        $project->link = $request->link;


        if ($request->photo) {
            $strpos = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $strpos);
            $ex = explode('/', $sub)[1];
            $name = time() . '.' . $ex;
            $img = Image::make($request->photo)->resize(200, 200);
            $uploadPath = public_path() . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR;
            $img->save($uploadPath . $name);
        } else {
            $name = $request->photo;
        }
        $project->photo = $name;

        $project->save();
    }

    public function update_project(Request $request, $id)
    {
        $project = Project::find($id);
        $this->validate($request, [
            'title' => 'required',
        ]);

        if ($project->photo != $request->photo) {
            $strpos = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $strpos);
            $ex = explode('/', $sub)[1];
            $name = time() . '.' . $ex;
            $img = Image::make($request->photo)->resize(200, 200);
            $uploadPath = public_path() . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR;
            $image = $uploadPath . $project->photo;
            $img->save($uploadPath . $name);
            if (file_exists($image))
                @unlink($image);
            else
                $name = $project->photo;

            $project->photo = $name;
        }
        $project->title = $request->title;
        $project->description = $request->description;
        $project->link = $request->link;
        $project->save();
    }

    public function delete_project($id)
    {
        $project = Project::findOrFail($id);
        if ($project) {
            $img_uri = public_path() . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $project->photo;
            @unlink($img_uri);
        }
        $project->delete();
    }
}
