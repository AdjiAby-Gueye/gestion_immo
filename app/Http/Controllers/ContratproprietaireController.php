<?php

namespace App\Http\Controllers;

use App\Contratproprietaire;
use App\Jobs\importContratProprietaireFileJob;
use App\Outil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContratproprietaireController extends Controller
{
    protected $queryName = 'contratproprietaires';
    protected $model = Contratproprietaire::class;
    protected $job = importContratProprietaireFileJob::class;


    public function save(Request $request){

            try{
                return DB::transaction(function () use ($request) {

                    $item = new $this->model;
                    $id = $request->input('id');
                    if(isset($id)){
                        $item = $this->model::find($id);
                        if (!$item) {
                            return response()->json(['error' => 'Enregistrement introuvable'], 404);
                        }
                    }

                    if (isset($request->is_tva) && ($request->is_tva == 'on' || $request->is_tva == true)) {
                        $request['is_tva']                = 1;
                    } else {
                        $request['is_tva']                = 0;
                    }

                    if (isset($request->is_brs) && ($request->is_brs == 'on' || $request->is_brs == true)) {
                        $request['is_brs']                = 1;
                    } else {
                        $request['is_brs']                = 0;
                    }

                    if (isset($request->is_tlv) && ($request->is_tlv == 'on' || $request->is_tlv == true)) {
                        $request['is_tlv']                = 1;
                    } else {
                        $request['is_tlv']                = 0;
                    }

                    $item->fill($request->all());
                    $item->save();

                    return Outil::redirectIfModeliSSaved($item, $this->queryName);
            });

            }catch(Exception $e){
                return Outil::getResponseError($e);
            }
    }

    public function delete($id)
    {
        return Outil::supprimerElement($this->model, $id);
    }

}
