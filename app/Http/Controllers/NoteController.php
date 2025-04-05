<?php

namespace App\Http\Controllers;
use App\Models\Note;
use App\Treits\HttpResponses;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    use HttpResponses;
    public function index(){

        $notes = Note::all();
        return $this->Success($notes);
    }
}
