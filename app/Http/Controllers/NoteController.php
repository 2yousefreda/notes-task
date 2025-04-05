<?php

namespace App\Http\Controllers;


use App\Http\Requests\NoteRequest;


use App\Service\NoteService;
class NoteController extends Controller
{
    
    public function index()
    {
        $Service=new NoteService;
        return $Service->index();
    }
    public function store(NoteRequest $Request)
    {
        $Service=new NoteService;
        return $Service->store($Request);
    }
    public function update(NoteRequest $Request, $NoteId){
        
        $Service=new NoteService;
        return $Service->update($Request, $NoteId);

    }
}
