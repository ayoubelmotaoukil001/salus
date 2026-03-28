<?php

namespace App\Http\Controllers;

use App\Models\HealthAdvice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use OpenApi\Attributes as OA;

class HealthAdviceController extends Controller
{
    #[OA\Get(
        path: '/api/ai/health-advice',
        summary: "Consulter l'historique des conseils AI",
        security: [['bearerAuth' => []]],
        tags: ['Intelligence Artificielle']
    )]
    #[OA\Response(response: 200, description: 'Historique récupéré')]
    public function index()
    {
        $advices = auth()->user()->healthAdvices()->latest()->get();
        return $this->success($advices, 'AI advice history retrieved');
    }

    #[OA\Post(
        path: '/api/ai/health-advice',
        summary: "Générer un conseil santé via AI",
        security: [['bearerAuth' => []]],
        tags: ['Intelligence Artificielle']
    )]
    #[OA\Response(response: 201, description: 'Conseil généré')]
    #[OA\Response(response: 400, description: 'Aucun symptôme trouvé')]
    public function generate()
    {
        $user = auth()->user();
        $latestSymptom = $user->symptoms()->latest()->first();

        if (!$latestSymptom) {
            return $this->error('No symptoms found', 400);
        }

        $advice = $user->healthAdvices()->create([
            'advice' => "Stay hydrated and rest for your " . $latestSymptom->name,
            'symptoms_context' => $latestSymptom->name
        ]);

        return $this->success($advice, 'AI Advice generated', 201);
    }
}
