<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengeluaran = Pengeluaran::all();
        return response()->json($pengeluaran);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_event' => 'required|integer',
            'kategori_pengeluaran' => 'required|string',
            'nama_pengeluaran' => 'required|string',
            'jumlah' => 'required|integer',
            'status_pembayaran' => 'string',
            'tanggal_transaksi' => 'date',
        ]);

        
        $kategori = $validatedData['kategori_pengeluaran'];
        if($kategori === 'tunai' || $kategori === 'non-tunai'){
            $validatedData['status_pembayaran'] = 'lunas';
        }elseif($kategori === 'dp'){
            $validatedData['status_pembayaran'] = 'pending';
        }else{
            return response()->json(['message' => 'Kategori Hanya menerima tunai, non-tunai, dan dp'], 406);
        }
        
        $validatedData['tanggal_transaksi'] = $request->input('tanggal_transaksi', now()->timezone('Asia/Jakarta'));

        $userId = Auth::id();
        $eventId = $validatedData['id_event'];
        $event = Event::find( $eventId );

        if( !$event || $event->id_user !== $userId ){
            return response()->json(['message' => 'Event not found'], 403);
        }

        $pengeluaran = Pengeluaran::create([
            'id_event' => $validatedData['id_event'],
            'kategori_pengeluaran' => $validatedData['kategori_pengeluaran'],
            'nama_pengeluaran' => $validatedData['nama_pengeluaran'],
            'jumlah' => $validatedData['jumlah'],
            'status_pembayaran' => $validatedData['status_pembayaran'],
            'tanggal_transaksi' => $validatedData['tanggal_transaksi'],
        ]);
        
        return response()->json(['message' => 'Pengeluaran Event Create Successfully', 'data' => $pengeluaran]);


    }

    /**
     * Display the specified resource.
     */
    public function show(pengeluaran $pengeluaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, pengeluaran $pengeluaran, $id)
    {
        
        $validatedData = $request->validate([
            'id_event' => 'required|integer',
            'kategori_pengeluaran' => 'required|string',
            'nama_pengeluaran' => 'required|string',
            'jumlah' => 'required|integer',
            'status_pembayaran' => 'string',
            'tanggal_transaksi' => 'date',
        ]);

        $pengeluaran = Pengeluaran::find($id);
        if (!$pengeluaran) {
            return response()->json(['message' => 'Pengeluaran event not found'], 404);
        }

        $userId = Auth::id();
        $eventId = $validatedData['id_event'];
        $event = Event::find($eventId);

        if (!$event || $event->id_user !== $userId) {
            return response()->json(['message' => 'Event Tidak Ditemukan atau Tidak Diizinkan'], 403);
        }

        $kategori = $validatedData['kategori_pengeluaran'];
        if ($kategori === 'tunai' || $kategori === 'non-tunai') {
            $validatedData['status_pembayaran'] = 'lunas';
        } elseif ($kategori === 'dp') {
            $validatedData['status_pembayaran'] = 'pending';
        } else {
            return response()->json(['message' => 'Kategori hanya menerima tunai, non-tunai, dan dp'], 406);
        }

        $validatedData['tanggal_transaksi'] = $request->input('tanggal_transaksi', now()->timezone('Asia/Jakarta'));

        $pengeluaran->update($validatedData);

        return response()->json([
            'message' => 'Pengeluaran Event Updated Successfully',
            'data' => $pengeluaran,
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(pengeluaran $pengeluaran, $id)
    {
        $pengeluaranEvent = Pengeluaran::find($id);

        if (!$pengeluaranEvent) {
            return response()->json(['message' => 'Pengeluaran event not found'], 404);
        }

        $pengeluaranEvent->delete();

        return response()->json(['message' => 'Pengeluaran Event Deleted Successfully']);
    }
    //
    public function search($nama_pengeluaran)
    {
        $results = Pengeluaran::where('nama_pengeluaran', 'like', '%' . $nama_pengeluaran . '%')->get();

        if ($results->isEmpty()) {
            return response()->json(['message' => 'Nama pengeluaran tidak ada'], 404);
        }
        
        return response()->json($results);
    }
}
