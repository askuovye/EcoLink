<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectionPoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CollectionPointController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ❗ CRUD usado pelo painel Laravel (não mexi)
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $points = CollectionPoint::paginate(10);
        return view('points.index', compact('points'));
    }

    public function map()
    {
        $points = CollectionPoint::all(['name', 'latitude', 'longitude', 'operating_hours', 'address']);
        return view('map', compact('points'));
    }

    public function getAllPoints()
    {
        return response()->json(CollectionPoint::all());
    }

    public function create()
    {
        return view('points.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();

        CollectionPoint::create($data);

        return redirect()->route('points.index')
            ->with('success', 'Ponto criado com sucesso!');
    }

    public function edit(CollectionPoint $point)
    {
        $user = Auth::user();

        if (!$user->is_admin && $point->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para editar este ponto.');
        }

        return view('points.edit', compact('point'));
    }

    public function update(Request $request, CollectionPoint $point)
    {
        $user = Auth::user();

        if (!$user->is_admin && $point->user_id !== $user->id) {
            abort(403, 'Você não pode atualizar este ponto.');
        }

        $point->update($request->all());

        return redirect()->route('points.index')
            ->with('success', 'Ponto atualizado com sucesso!');
    }

    public function destroy(CollectionPoint $point)
    {
        $user = Auth::user();

        if (!$user->is_admin && $point->user_id !== $user->id) {
            abort(403, 'Você não pode excluir este ponto.');
        }

        $point->delete();

        return redirect()->route('points.index')
            ->with('success', 'Ponto excluído com sucesso!');
    }

    public function verify(CollectionPoint $point)
    {
        $user = Auth::user();

        if (!$user->is_admin) {
            abort(403, 'Apenas administradores podem aprovar pontos.');
        }

        $point->verified = true;
        $point->save();

        return redirect()->route('points.index')
            ->with('success', 'Ponto verificado com sucesso!');
    }


    /*
    |--------------------------------------------------------------------------
    | 🌎 API REST PARA O APP FLUTTER (RECOMENDADO)
    |--------------------------------------------------------------------------
    */

    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        // Usa sempre a mesma chave
        $key = env('GOOGLE_PLACES_API_KEY');

        $response = Http::get(
            'https://maps.googleapis.com/maps/api/place/nearbysearch/json',
            [
                'location' => "$lat,$lng",
                'radius'   => 5000,
                'keyword'  => 'reciclagem|descarte|ponto de coleta',
                'language' => 'pt-BR',
                'key'      => $key,
            ]
        );

        if ($response->failed()) {
            return response()->json([
                'error' => 'Erro ao consultar Google Places',
                'details' => $response->body()
            ], 500);
        }

        $results = $response->json()['results'] ?? [];

        $output = [];

        foreach ($results as $place) {
            $output[] = [
                'name' => $place['name'],
                'lat'  => $place['geometry']['location']['lat'],
                'lng'  => $place['geometry']['location']['lng'],
                'address' => $place['vicinity'] ?? '',
            ];
        }

        // Salva no banco com updateOrCreate()
        foreach ($output as $p) {
            CollectionPoint::updateOrCreate(
                ['name' => $p['name'], 'latitude' => $p['lat'], 'longitude' => $p['lng']],
                [
                    'name' => $p['name'],
                    'latitude' => $p['lat'],
                    'longitude' => $p['lng'],
                    'address' => $p['address'],
                ]
            );
        }

        return response()->json($output);
    }
}
