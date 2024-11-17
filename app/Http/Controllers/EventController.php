<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $allEvent = Event::all();
        return response()->json($allEvent);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'nama_event' => 'required',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'lokasi' => 'required',
        ]);

        $userId = Auth::id();

        $event = Event::create([
            'id_user' => $userId,
            'nama_event' => $validatedData['nama_event'],
            'deskripsi' => $validatedData['deskripsi'],
            'tanggal_mulai' => $validatedData['tanggal_mulai'],
            'tanggal_selesai' => $validatedData['tanggal_selesai'],
            'lokasi' => $validatedData['lokasi'],
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $event,
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_event' => 'required',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'lokasi' => 'required',

        ]);

        $userId = Auth::id();

        $event = Event::find($id);

        if(!$event || $event->id_user !== $userId){
            return response()->json(['message'=> 'Event tidak ditemukan'], 403);
        }

        $event->update($validatedData);

        return response()->json($event);
    }

    public function destroy(string $id)
    {
        $userId = Auth::id();
        $event = Event::find($id);

        if (!$event || $event->id_user !== $userId){
            return response()->json(['message'=> 'event tidak ditemukan atau anda tidak login'],403);
        }

        $event->delete();
        return response()->json(['message' => 'Event berhasil dihapus']);
    }

    public function search($nama_event)
    {
        $results = Event::where('nama_event', 'like', '%' . $nama_event . '%')->get();

        if ($results->isEmpty()) {
            return response()->json(['message' => 'Event tidak ada'], 404);
        }
        
        return response()->json($results);
    }
}