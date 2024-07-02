<?php

namespace App\Http\Controllers;

use App\Helpers\ApiCodes;
use App\Models\Department;
use App\Models\KnowledgeBase;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KnowledgeBaseController extends Controller
{
    use ApiTrait;

    public function index(Request $request)
    {
        $query = KnowledgeBase::with('department', 'user');
        if ($request->has('kbCategory') && $request->kbCategory != 'all') {
            $data = $query->where('department_id', '=', $request->kbCategory);
        } elseif ($request->has('kbPinned') && $request->kbPinned != 'all') {
            $data = $query->where('pinned', '=', $request->kbPinned);
        } elseif ($request->has('kbStatus') && $request->kbStatus != 'all') {
            $data = $query->where('status', '=', $request->kbStatus);
        } else {
            $data = $query;
        }

        return $this->successResponse($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department' => 'required',
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $department = $request->department;
        $title = $request->title;
        $content = $request->get('content');
        $status = $request->status;

        $created = KnowledgeBase::create([
            'department_id' => $department,
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'user_id' => Auth::id(),
        ]);

        if ($created) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function show($id)
    {
        $kb = KnowledgeBase::find($id);
        $departments = Department::all();

        return $this->successResponse(['kb' => $kb, 'departments' => $departments]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'department' => 'required',
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $department = $request->department;
        $title = $request->title;
        $content = $request->get('content');
        $status = $request->status;

        $kb = KnowledgeBase::find($id);
        $kb->department_id = $department;
        $kb->title = $title;
        $kb->content = $content;
        $kb->status = $status;

        if ($kb->save()) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function destroy($id)
    {
        $done = KnowledgeBase::where('id', $id)->delete();
        if ($done) {
            return response()->json(['success' => 'Deleted successfully']);
        }

        return $this->generalError('Failed to delete!');
    }

    public function KnowledgeBaseIndex()
    {
        $categories = Department::all();

        $posts = KnowledgeBase::latest()->where('status', KnowledgeBase::PUBLISHED)->limit('10')->get();

        return $this->successResponse(['categories' => $categories, 'posts' => $posts]);
    }

    public function viewArticle($id)
    {
        $postKey = 'post_' . $id;

        // Check if blog session key exists
        // If not, update view_count and create session key
        if (!Session::has($postKey)) {
            KnowledgeBase::where('id', $id)->increment('view_count');
            Session::put($postKey, 1);
        }

        $post = KnowledgeBase::with('user')->withCount('satisfiedVote', 'disSatisfiedVote')->where('status', KnowledgeBase::PUBLISHED)->findOrFail($id);

        return $this->successResponse(['post' => $post]);
    }

    public function categoryPost(Department $category)
    {
        $posts = KnowledgeBase::where('status', KnowledgeBase::PUBLISHED)->where('department_id', $category->id)->paginate(15);

        return $this->successResponse(['posts' => $posts, 'category' => $category]);
    }

    public function searchArticles(Request $request)
    {
        $search = $request->search;

        $posts = KnowledgeBase::latest()->where('status', KnowledgeBase::PUBLISHED)->where('title', 'LIKE', "%$search%")->paginate(15);

        $view = view('search', compact('posts', 'search'))->render();

        return response()->json(['posts' => $view]);
    }

    public function pinnedArticle($id)
    {
        $kb = KnowledgeBase::find($id);
        if ($kb->pinned == 1) {
            $kb->update([
                'pinned' => 0
            ]);
        } else {
            $kb->update([
                'pinned' => 1
            ]);
        }

        return response()->json(['success' => 'success'], 200);

    }
}
