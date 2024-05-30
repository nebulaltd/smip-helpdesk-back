<?php

namespace App\Traits;

use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

trait VoteTrait
{
    protected function voteForKB($request, $vote, $id)
    {
        $satisfied = 0;
        $dissatisfied = 0;

        if ($request->satisfaction == 'yes') {
            $satisfied = 1;
            Session::push('vote_yes','yes_'.$id);
        } else {
            $dissatisfied = 1;
            Session::push('vote_no','no_'.$id);
        }

        $saved = $vote->vote()->create([
                    'satisfied' => $satisfied,
                    'dissatisfied' => $dissatisfied
                ]);

        if ($saved){
            return true;
        }else{
            return false;
        }
    }

    protected function KBStatusChange($id)
    {
        $kb = KnowledgeBase::find($id);

        if ($kb->status == 0) {
            $kb->status = 1;
        } else {
            $kb->status = 0;
        }

        if ($kb->save()) {
            return true;
        } else {
            return false;
        }
    }

    protected function KBMakePinned($id)
    {
        $kb = KnowledgeBase::find($id);

        if ($kb->pinned == 0) {
            $kb->pinned = 1;
        } else {
            $kb->pinned = 0;
        }

        if ($kb->save()) {
            return true;
        } else {
            return false;
        }
    }
}
