<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DoctorController extends Controller
{
    #[OA\Get(
        path: '/api/doctors',
        summary: "Lister tous les médecins disponibles",
        security: [['bearerAuth' => []]],
        tags: ['Médecins']
    )]
    #[OA\Response(response: 200, description: 'Liste récupérée')]
    public function index()
    {
        $doctors = Doctor::all();
        return $this->success($doctors, 'Doctors retrieved successfully');
    }

    #[OA\Get(
        path: '/api/doctors/search',
        summary: "Rechercher des médecins par spécialité ou ville",
        security: [['bearerAuth' => []]],
        tags: ['Médecins']
    )]
    #[OA\Parameter(name: 'specialty', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'city', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 200, description: 'Résultats de recherche')]
    public function search(Request $request)
    {
        $query = Doctor::query();

        if ($request->filled('specialty')) {
            $query->where('specialty', 'like', '%' . $request->specialty . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        return $this->success($query->get(), 'Search results');
    }

    #[OA\Get(
        path: '/api/doctors/{id}',
        summary: "Détails d'un médecin spécifique",
        security: [['bearerAuth' => []]],
        tags: ['Médecins']
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Détails récupérés')]
    public function show(Doctor $doctor)
    {
        return $this->success($doctor, 'Doctor details retrieved');
    }
}
