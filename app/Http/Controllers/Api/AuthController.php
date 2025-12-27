<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * User login
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
                'device_name' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            // Get user type (driver, admin, or agent)
            $userType = $user->getUserType();

            if (!$userType) {
                return response()->json([
                    'success' => false,
                    'message' => 'User role not found. Please contact administrator.',
                ], 403);
            }

            // Create token with ability based on user type
            $token = $user->createToken($request->device_name, [$userType])->plainTextToken;

            // Get additional profile data based on user type
            $profileData = $this->getUserProfileData($user, $userType);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'user_type' => $userType,
                    ],
                    'profile' => $profileData,
                    'token' => $token,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * User registration
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|max:20',
                'user_type' => 'required|in:driver,agent',
                'device_name' => 'required|string',
                
                // Driver-specific fields
                'license_number' => 'required_if:user_type,driver|string|max:255',
                'license_expiry' => 'required_if:user_type,driver|date',
                'id_number' => 'required_if:user_type,driver|string|max:255',
                
                // Agent-specific fields
                'company_name' => 'required_if:user_type,agent|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign role and create profile based on user type
            if ($request->user_type === 'driver') {
                // Assign driver role (create if doesn't exist)
                $user->assignRole('driver');
                
                // Create driver profile
                $driver = Driver::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'license_number' => $request->license_number,
                    'license_expiry' => $request->license_expiry,
                    'id_number' => $request->id_number,
                    'status' => 'pending', // Pending approval
                ]);

                $profileData = [
                    'driver_id' => $driver->id,
                    'license_number' => $driver->license_number,
                    'license_expiry' => $driver->license_expiry,
                    'status' => $driver->status,
                ];

            } elseif ($request->user_type === 'agent') {
                // Assign agent role
                $user->assignRole('agent');
                
                // Create agent profile
                $agent = Agent::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'company_name' => $request->company_name,
                    'status' => 'pending', // Pending approval
                    'commission_type' => 'percentage',
                    'commission_value' => 0,
                    'credit_limit' => 0,
                ]);

                $profileData = [
                    'agent_id' => $agent->id,
                    'company_name' => $agent->company_name,
                    'status' => $agent->status,
                ];
            }

            // Create authentication token
            $token = $user->createToken($request->device_name, [$request->user_type])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registration successful. Your account is pending approval.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'user_type' => $request->user_type,
                    ],
                    'profile' => $profileData,
                    'token' => $token,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get authenticated user profile
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $userType = $user->getUserType();
            $profileData = $this->getUserProfileData($user, $userType);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'user_type' => $userType,
                    ],
                    'profile' => $profileData,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout user
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout from all devices
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function logoutAll(Request $request): JsonResponse
    {
        try {
            // Revoke all tokens
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out from all devices successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user profile data based on user type
     * 
     * @param User $user
     * @param string $userType
     * @return array
     */
    private function getUserProfileData(User $user, string $userType): array
    {
        $profileData = [];

        switch ($userType) {
            case 'driver':
                $driver = $user->driver;
                if ($driver) {
                    $profileData = [
                        'driver_id' => $driver->id,
                        'phone' => $driver->phone,
                        'license_number' => $driver->license_number,
                        'license_expiry' => $driver->license_expiry,
                        'id_number' => $driver->id_number,
                        'status' => $driver->status,
                        'rating' => $driver->rating,
                        'total_trips' => $driver->total_trips,
                        'photo' => $driver->photo,
                    ];
                }
                break;

            case 'agent':
                $agent = Agent::where('email', $user->email)->first();
                if ($agent) {
                    $profileData = [
                        'agent_id' => $agent->id,
                        'phone' => $agent->phone,
                        'company_name' => $agent->company_name,
                        'status' => $agent->status,
                        'commission_type' => $agent->commission_type,
                        'commission_value' => $agent->commission_value,
                        'credit_limit' => $agent->credit_limit,
                        'credit_used' => $agent->credit_used,
                        'available_budget' => $agent->available_budget,
                    ];
                }
                break;

            case 'admin':
                $profileData = [
                    'role' => 'admin',
                    'permissions' => 'full_access',
                ];
                break;
        }

        return $profileData;
    }
}
