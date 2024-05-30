<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KnowledgeBase;
use App\Traits\VoteTrait;
use Illuminate\Support\Facades\Session;

class VoteController extends Controller
{
	use VoteTrait;

    public function KBvoteYes(Request $request, $id)
    {
        $vote = KnowledgeBase::find($id);

        $saved = $this->voteForKB($request, $vote, $id);

        if ($saved) {
            $notify = storeNotify('Your vote');
        }else{
            $notify = errorNotify("Vote");
        }

        return redirect()->back()->with($notify);
    }
}
