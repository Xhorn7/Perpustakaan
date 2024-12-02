<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Controllers\MyUtil;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MyUtil::sendresponse(Buku::all(), 'OK');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function search(Request $request)
    {
        $cari = $request->cari;
        $start = $request->start;
        $limit = $request->limit;
        $count = DB::table('bukus')
            ->where('judul', 'like', "%".$cari."%")
            ->orWhere('pengarang', 'like', "%".$cari."%")
            ->count();

        $buku = DB::table('bukus')
            ->where('judul', 'like', "%".$cari."%")
            ->orWhere('pengarang', 'like', "%".$cari."%")
            ->offset($start)
            ->limit($limit)
            ->orderBy('judul')
            ->get();

        $obj = new \stdClass();
        $obj->count = $count;
        $obj->buku = $buku;

        return response()->json($obj);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'isbn' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'tahun' => 'required|integer',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $buku = new Buku();
        $buku->isbn = $request->isbn;
        $buku->judul = $request->judul;
        $buku->pengarang = $request->pengarang;
        $buku->tahun = $request->tahun;
        $buku->save();

        return response()->json(['success' => true, 'data' => $buku], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $buku = Buku::findOrFail($id);
            return response()->json(['success' => true, 'data' => $buku], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Book not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            'isbn' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'tahun' => 'required|integer',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $buku = Buku::findOrFail($id);
            $buku->isbn = $request->isbn;
            $buku->judul = $request->judul;
            $buku->pengarang = $request->pengarang;
            $buku->tahun = $request->tahun;
            $buku->save();

            return response()->json(['success' => true, 'data' => $buku], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Book not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $buku->delete();

            return response()->json(['success' => true, 'message' => 'Book deleted'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Book not found'], 404);
        }
    }
}
