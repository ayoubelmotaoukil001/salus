<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA; // ركز هنا.. غيرنا Annotations لـ Attributes

#[OA\Info(version: "1.0.0", title: "Salus AI API", description: "Documentation de l'API Salus")]
#[OA\Server(url: "http://localhost:8000", description: "Serveur Local")]
abstract class Controller
{
    public function success($data, $message = 'Success', $status = 200)
    {
        return response()->json(['success' => true, 'data' => $data, 'message' => $message], $status);
    }

    public function error($message = 'Error', $status = 400, $errors = [])
    {
        return response()->json(['success' => false, 'errors' => $errors, 'message' => $message], $status);
    }
}
