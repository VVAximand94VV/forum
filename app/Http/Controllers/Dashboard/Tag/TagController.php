<?php

namespace App\Http\Controllers\Dashboard\Tag;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Paginate\PaginateRequest;
use App\Http\Requests\Dashboard\Tag\TagStoreRequest;
use App\Http\Requests\Dashboard\Tag\TagUpdateRequest;
use App\Http\Resources\Dashboard\Forum\TagResource;
use App\Models\Tag;
use App\Services\AuthService;

class TagController extends Controller
{

    public function index(PaginateRequest $request)
    {
        $paginate = $request->validated();
        $tags = Tag::paginate($paginate['entriesOnPage'], ['*'], 'page', $paginate['page']);
        return TagResource::collection($tags);
    }

    /**
     * @param TagStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TagStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = AuthService::getAuthorizedUser($request);
        $data['authorId'] = $user->id;
        $tag = Tag::firstOrCreate($data);
        return response()->json([
            'message' => 'Tag created!',
            'tag' => new TagResource($tag),
        ]);
    }

    /**
     * @param Tag $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Tag $tag): \Illuminate\Http\JsonResponse
    {
        return response()->json(['tag' => new TagResource($tag)]);
    }

    public function update(TagUpdateRequest $request, Tag $tag)
    {
        $data = $request->validated();
        foreach($data as $key => $value){
            $tag->$key = $value;
        }
        $tag->save();
        return response()->json(['message' => 'Tag updated!']);
    }

    /**
     * @param Tag $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Tag $tag): \Illuminate\Http\JsonResponse
    {
        $tag->delete();
        return response()->json(['message' => 'Tag deleted successfully!']);
    }

    /**
     * @param Tag $tag
     * @return \Illuminate\Http\JsonResponse
     * Change tag status(visiblity)
     */
    public function status(Tag $tag): \Illuminate\Http\JsonResponse
    {
        $tag->status = !$tag->status;
        $tag->save();
        return response()->json(['message' => 'Tag status changed.']);
    }
}
