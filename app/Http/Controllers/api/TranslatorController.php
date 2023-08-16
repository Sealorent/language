<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class TranslatorController extends Controller
{
    function index($id)
    {
        $data = DB::table('translator')->where('user_id', $id)->get();
        return response()->json([
            'message' => 'Data retrieved successfully',
            'is_correct' => true,
            'data' => $data,
        ], 200);
    }

    function store(Request $request)
    {
        Request::validate([
            'user_id' => ['required'],
            'word' => ['required'],
            // 'phonetics' => ['required'],
            // 'audio' => ['required'],
            // 'partOfSpeech' => ['required'],
            // 'definition' => ['required'],
        ]);

        DB::table('translator')->insert([
            'user_id' => Request::get('user_id'),
            'word' => Request::get('word'),
            'phonetics' => Request::get('phonetics'),
            'audio' => Request::get('audio'),
            'partOfSpeech' => Request::get('partOfSpeech'),
            'definition' => Request::get('definition'),
        ]);

        return response()->json([
            'message' => 'Data saved successfully',
            'is_correct' => true,
        ], 201);
    }

    function setFavorite($id)
    {
        // update with id
        DB::table('translator')->where('id', $id)->update([
            'isFavourite' => true,
        ]);
        $data = DB::table('translator')->where('id', $id)->get();
        return response()->json([
            'message' => 'Data saved successfully',
            'is_correct' => true,
            'data' => $data
        ], 201);
    }

    function setUnFavorite($id)
    {
        // update with id
        DB::table('translator')->where('id', $id)->update([
            'isFavourite' => false,
        ]);

        return response()->json([
            'message' => 'Data saved successfully',
            'is_correct' => true,
        ], 201);
    }

    function delete($id)
    {
        //delete with id
        try {
            DB::table('translator')->where('id', $id)->delete();
            return response()->json([
                'message' => 'Data saved successfully',
                'is_correct' => true,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'is_correct' => true,
            ], 201);
        }
    }
}
