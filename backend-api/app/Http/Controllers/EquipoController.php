<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Equipo::all());
    }

    public function validar(Request $request): JsonResponse
    {
        $data = $request->validate([
            'codigos' => ['required', 'array'],
            'codigos.*' => ['string'],
        ]);

        $codigos = $data['codigos'];
        $existentes = Equipo::whereIn('codigo', $codigos)->pluck('codigo')->all();

        $encontrados = array_values(array_filter(
            $codigos,
            fn (string $codigo) => in_array($codigo, $existentes, true)
        ));
        $noEncontrados = array_values(array_filter(
            $codigos,
            fn (string $codigo) => !in_array($codigo, $existentes, true)
        ));

        return response()->json([
            'encontrados' => $encontrados,
            'no_encontrados' => $noEncontrados,
        ]);
    }
}
