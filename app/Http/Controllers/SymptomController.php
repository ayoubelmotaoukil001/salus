<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class SymptomController extends Controller
{
    #[OA\Get(
        path: '/api/symptoms',
        summary: "Lister les symptômes de l'utilisateur",
        security: [['bearerAuth' => []]],
        tags: ['Symptômes']
    )]
    #[OA\Response(response: 200, description: 'Liste des symptômes récupérée')]
    public function index()
    {
        $symptoms = auth()->user()->symptoms()->latest()->get();
        return $this->success($symptoms, 'Symptoms list retrieved');
    }

    #[OA\Post(
        path: '/api/symptoms',
        summary: "Enregistrer un nouveau symptôme",
        security: [['bearerAuth' => []]],
        tags: ['Symptômes']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'severity', 'date_recorded'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Migraine'),
                new OA\Property(property: 'severity', type: 'string', example: 'moderate'),
                new OA\Property(property: 'description', type: 'string', example: 'Douleur intense'),
                new OA\Property(property: 'date_recorded', type: 'string', format: 'date', example: '2026-03-25'),
                new OA\Property(property: 'notes', type: 'string', example: 'Sensibilité à la lumière')
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Symptôme enregistré avec succès')]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'severity' => 'required|in:mild,moderate,severe',
            'description' => 'nullable|string',
            'date_recorded' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $symptom = auth()->user()->symptoms()->create($request->all());

        return $this->success($symptom, 'Symptom recorded successfully', 201);
    }

    #[OA\Get(
        path: '/api/symptoms/{id}',
        summary: "Détail d'un symptôme",
        security: [['bearerAuth' => []]],
        tags: ['Symptômes']
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Détails récupérés')]
    public function show(Symptom $symptom)
    {
        if ($symptom->user_id !== auth()->id()) {
            return $this->error('Unauthorized', 403);
        }
        return $this->success($symptom, 'Symptom details retrieved');
    }

    #[OA\Put(
        path: '/api/symptoms/{id}',
        summary: "Modifier un symptôme",
        security: [['bearerAuth' => []]],
        tags: ['Symptômes']
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'severity', type: 'string'),
                new OA\Property(property: 'description', type: 'string')
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Symptôme mis à jour')]
    public function update(Request $request, Symptom $symptom)
    {
        if ($symptom->user_id !== auth()->id()) {
            return $this->error('Unauthorized', 403);
        }

        $symptom->update($request->all());
        return $this->success($symptom, 'Symptom updated successfully');
    }

    #[OA\Delete(
        path: '/api/symptoms/{id}',
        summary: "Supprimer un symptôme",
        security: [['bearerAuth' => []]],
        tags: ['Symptômes']
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Symptôme supprimé')]
    public function destroy(Symptom $symptom)
    {
        if ($symptom->user_id !== auth()->id()) {
            return $this->error('Unauthorized', 403);
        }
        $symptom->delete();
        return $this->success(null, 'Symptom deleted successfully');
    }
}
