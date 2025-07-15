<?php

namespace App\Http\Controllers;

use App\Appartement;
use App\Etatappartement;
use App\Intervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class EtatappartementApiController extends Controller
{
    public function index()
    {
        $etatappartements = Etatappartement::with(['appartements'])->get() ;
        return response($etatappartements, 200) ;
    }

   /* public function store(Request $request)
    {
        $validation = Validator::make($request->all(), ['designation' => 'required', 'description' => 'required', ]);
        if ($validation->fails())
        {
            return response()->json(['error' => true, 'messages' => $validation->errors(), ], 200);
        }
        else
        {
            $comment = new Comment;
            $comment->designation = $request->input('designation');
            $comment->description = $request->input('description');
            $comment->user_id = 1;
            $comment->save();
            Event(new RealTimeMessage('keep it up !'));
            return response()->json(['error' => false, 'customer' => $comment, Event(new RealTimeMessage('keep it up !'))], 200);
        }
    }*/

    public function getOne($id)
    {
        $etatappartement = Etatappartement::with(['appartements'])->find($id);
        if (is_null($etatappartement))
        {
            return response()->json(['error' => true, 'message' => "Etat Appartement inéxistant", ], 404);
        }
        return response()->json($etatappartement, 200);
    }

    public function update(Request $request, $id)
    {
        {
            $comment = Intervention::find($id);
            $comment->designation = $request->input('designation');
            $comment->description = $request->input('description');
            $comment->save();
            return response()->json(['error' => false, 'comment' => $comment, ], 200);
        }
    }

    public function destroy($id)
    {
        $comment = Intervention::find($id);
        if (is_null($comment))
        {
            return response()->json(['error' => true, 'message' => "Record with id # $id not found", ], 404);
        }
        $comment->delete();
        return response()->json(['error' => false, 'message' => "Customer record successfully deleted id # $id", ], 200);
    }
}
