<?php

namespace App\Http\Controllers\Admin\Forum;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Forum\ForumRequest;
use App\Models\Forum;

class ForumController extends Controller
{

    protected function index()
    {
        $forums = Forum::all();
        return response()->json(['forums' => $forums]);
    }

    protected function show(Forum $forum)
    {
        return response()->json(['forum' => $forum]);
    }

    protected function store(ForumRequest $request)
    {
        $data = $request->validated();
        if($data['type']==0 && $data['parentId']!=0){
            unset($data['parentId']);
        }
        $forum = Forum::firstOrCreate($data);
        return response()->json(['message' => 'Forum created!']);
    }

    protected function update(Forum $forum, ForumRequest $request)
    {
        $data = $request->validated();
        foreach($data as $key => $value){
            $forum->$key = $value;
        }
        $forum->save();
        return response()->json(['message' => 'Forum updated!']);

    }

    protected function delete(Forum $forum)
    {
        if($forum->children){
            foreach($forum->children as $child){
                $child->parentId = $forum->parent->id ?? null;
                $child->type = $child->parentId ? 1:0;
                $child->save();
            }
        }
        $forum->delete();
        return response()->json(['message' => 'Forum deleted successfully!']);
    }

    public function status(Forum $forum)
    {
        $forum->status = !$forum->status;
        $forum->save();
        return response()->json(['message' => 'Forum status changed!']);
    }

}
