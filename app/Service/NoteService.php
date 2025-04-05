<?php
namespace App\Service;
use App\Models\Note;
use App\Models\Tenant;
use App\Treits\HttpResponses;
use App\Http\Requests\NoteRequest;
use Illuminate\Support\Facades\Auth;
class NoteService
{


    use HttpResponses;
    
    public function index()
    {
        $Tenant = Tenant::find(Auth::user()->tenant_id);
        return $Tenant->run(function () {
            $notes = Note::all();
            return $this->Success($notes);
        });
    }

    public function store(NoteRequest $Request)
    {
        $Tenant = Tenant::find(Auth::user()->tenant_id);
        return  $Tenant->run(function () use ($Request) {
            $Note = Note::create($Request->validated());
            return $this->Success($Note);
        });
    }
    public function update(NoteRequest $Request, $NoteId){
        
        $Tenant = Tenant::find(Auth::user()->tenant_id);
        
        return  $Tenant->run(function () use ($NoteId, $Request) {
            $Note = Note::find($NoteId);
            if (!$Note) {
                return $this->Error('', 'Note not found', 404);
            }
            $Note->update($Request->validated());
            return $this->Success($Note);
        });
    }

}