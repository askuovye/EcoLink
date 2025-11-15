<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectionPoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CollectionPointController extends Controller
{
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
        $data['user_id'] = Auth::id(); // Define o dono do ponto

        CollectionPoint::create($data);

        return redirect()->route('points.index')
            ->with('success', 'Ponto criado com sucesso!');
    }

    public function edit(CollectionPoint $point)
    {
        $user = Auth::user();

        // Se não for admin e não for dono do ponto
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

    // Aprovar/Verificar ponto ⇒ apenas ADMIN
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

    // Buscar locais próximos via Google
    public function getNearbyPlaces($latitude, $longitude, $radius = 3000)
    {
        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $query = urlencode('ponto de coleta de lixo');

        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query={$query}&location={$latitude},{$longitude}&radius={$radius}&key={$apiKey}";

        $response = Http::get($url);
        $places = $response->json()['results'] ?? [];

        $points = [];

        foreach ($places as $place) {
            $points[] = [
                'name' => $place['name'],
                'latitude' => $place['geometry']['location']['lat'],
                'longitude' => $place['geometry']['location']['lng'],
            ];
        }

        // Salva no banco
        foreach ($points as $p) {
            CollectionPoint::updateOrCreate(
                ['name' => $p['name']],
                ['latitude' => $p['latitude'], 'longitude' => $p['longitude']]
            );
        }

        return response()->json($points);
    }
}
