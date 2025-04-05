<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Tenant;
use App\Treits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    use HttpResponses;
    
    public function index()
    {
        $tenant = Tenant::find(Auth::user()->tenant_id);
        return $tenant->run(function () {
            $notes = Note::all();
            return $this->Success($notes);
        });
    }

    public function store(Request $request)
    {
        $tenant = Tenant::find(Auth::user()->tenant_id);
        return  $tenant->run(function () use ($request) {
            $note = Note::create([
                'content' => $request->content,
            ]);
            return $this->Success($note);
        });
    }
}
