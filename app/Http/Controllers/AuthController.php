<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/register',
        summary: "Inscription d'un nouvel utilisateur",
        tags: ['Authentification']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email', 'password', 'password_confirmation'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Ayoub'),
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                new OA\Property(property: 'password_confirmation', type: 'string', example: 'password123')
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Utilisateur créé avec succès')]
    #[OA\Response(response: 422, description: 'Erreur de validation')]
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => $user,
            'access_token' => $token
        ], 'Registered successfully', 201);
    }

    #[OA\Post(
        path: '/api/login',
        summary: "Connexion de l'utilisateur",
        tags: ['Authentification']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123')
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Connexion réussie')]
    #[OA\Response(response: 401, description: 'Identifiants invalides')]
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => $user,
            'access_token' => $token
        ], 'Logged in successfully');
    }

    #[OA\Get(
        path: '/api/me',
        summary: "Obtenir les informations de l'utilisateur connecté",
        security: [['bearerAuth' => []]],
        tags: ['Authentification']
    )]
    #[OA\Response(response: 200, description: 'Succès')]
    #[OA\Response(response: 401, description: 'Non autorisé')]
    public function me(Request $request)
    {
        return $this->success($request->user(), 'Profile retrieved');
    }

    #[OA\Post(
        path: '/api/logout',
        summary: "Déconnexion (Révocation du token)",
        security: [['bearerAuth' => []]],
        tags: ['Authentification']
    )]
    #[OA\Response(response: 200, description: 'Déconnexion réussie')]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logged out successfully');
    }
}
