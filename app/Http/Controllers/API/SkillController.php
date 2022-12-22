<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Lib\DatatableResponder;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function get_all_skills()
    {
        $skills = Skill::with('service')->orderBy('id', 'DESC')->get();
        return DatatableResponder::sendResponse($skills, $skills->count());
    }

    public function create_skill(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'proficiency' => 'max:100|integer'
        ]);

        $skill = new Skill;
        $skill->name = $request->name;
        $skill->proficiency = $request->proficiency;
        $skill->service_id = $request->service_id;
        $skill->save();
    }

    public function update_skill(Request $request, $id)
    {
        $skill = Skill::find($id);
        $this->validate($request, [
            'name' => 'required',
            'proficiency' => 'max:100|integer'
        ]);
        $skill->name = $request->name;
        $skill->proficiency = $request->proficiency;
        $skill->service_id = $request->service_id;
        $skill->save();
    }

    public function delete_skill($id)
    {
        $skill = Skill::findOrFail($id);

        $skill->delete();
    }
}
