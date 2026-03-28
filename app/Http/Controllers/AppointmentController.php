<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class AppointmentController extends Controller
{
    #[OA\Get(
        path: '/api/appointments',
        summary: "Lister les rendez-vous de l'utilisateur connecté",
        security: [['bearerAuth' => []]],
        tags: ['Rendez-vous']
    )]
    #[OA\Response(response: 200, description: 'Liste récupérée')]
    public function index()
    {
        $appointments = auth()->user()->appointments()->with('doctor')->get();
        return $this->success($appointments, 'Appointments list retrieved');
    }

    #[OA\Post(
        path: '/api/appointments',
        summary: "Prendre un nouveau rendez-vous",
        security: [['bearerAuth' => []]],
        tags: ['Rendez-vous']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['doctor_id', 'appointment_date'],
            properties: [
                new OA\Property(property: 'doctor_id', type: 'integer', example: 1),
                new OA\Property(property: 'appointment_date', type: 'string', format: 'date-time', example: '2026-06-01 10:00:00'),
                new OA\Property(property: 'notes', type: 'string', example: 'Consultation annuelle')
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Rendez-vous créé avec succès')]
    #[OA\Response(response: 422, description: 'Erreur de validation')]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $appointment = auth()->user()->appointments()->create($request->all());

        return $this->success($appointment, 'Appointment booked successfully', 201);
    }

    #[OA\Delete(
        path: '/api/appointments/{id}',
        summary: "Annuler un rendez-vous",
        security: [['bearerAuth' => []]],
        tags: ['Rendez-vous']
    )]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Rendez-vous annulé')]
    public function destroy(Appointment $appointment)
    {
        if ($appointment->user_id !== auth()->id()) {
            return $this->error('Unauthorized', 403);
        }

        $appointment->delete();
        return $this->success(null, 'Appointment cancelled successfully');
    }
}
