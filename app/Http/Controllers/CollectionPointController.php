<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\CollectionPointService;

class CollectionPointController extends Controller
{
    private CollectionPointService $service;

    public function __construct(CollectionPointService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $points = $this->service->paginate(10);
        return view('points.index', compact('points'));
    }

    public function map()
    {
        $points = $this->service->all(['name', 'latitude', 'longitude', 'operating_hours', 'address']);
        return view('map', compact('points'));
    }

    public function getAllPoints()
    {
        return response()->json($this->service->all());
    }

    public function create()
    {
        return view('points.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();

        $this->service->store($data);

        return redirect()
            ->route('points.index')
            ->with('success', 'Ponto criado com sucesso!');
    }

    public function edit($pointId)
    {
        $user = Auth::user();
        $point = $this->service->show($pointId);

        if (!$user->is_admin && $point->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para editar este ponto.');
        }

        return view('points.edit', compact('point'));
    }

    public function update(Request $request, $pointId)
    {
        $user = Auth::user();
        $point = $this->service->show($pointId);

        if (!$user->is_admin && $point->user_id !== $user->id) {
            abort(403, 'Você não pode atualizar este ponto.');
        }

        $this->service->update($request->all(), $pointId);

        return redirect()
            ->route('points.index')
            ->with('success', 'Ponto atualizado com sucesso!');
    }

    public function destroy($pointId)
    {
        $user = Auth::user();
        $point = $this->service->show($pointId);

        if (!$user->is_admin && $point->user_id !== $user->id) {
            abort(403, 'Você não pode excluir este ponto.');
        }

        $this->service->destroy($pointId);

        return redirect()
            ->route('points.index')
            ->with('success', 'Ponto excluído com sucesso!');
    }

    public function verify($pointId)
    {
        $user = Auth::user();

        if (!$user->is_admin) {
            abort(403, 'Apenas administradores podem aprovar pontos.');
        }

        $this->service->verifyPoint($pointId);

        return redirect()
            ->route('points.index')
            ->with('success', 'Ponto verificado com sucesso!');
    }
}
