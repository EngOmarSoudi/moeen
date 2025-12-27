# MOEAN System - Authentication API Documentation

## Base URL
```
https://your-domain.com/api
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {token}
```

---

## Public Endpoints (No Authentication Required)

### 1. Login
**Endpoint:** `POST /auth/login`

**Description:** Authenticate user and get access token. Automatically detects if the user is a driver, admin, or agent.

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123",
    "device_name": "Android App v1.0"
}
```

**Success Response (200 OK):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "user_type": "driver"
        },
        "profile": {
            "driver_id": 5,
            "phone": "+966123456789",
            "license_number": "LIC123456",
            "license_expiry": "2026-12-31",
            "id_number": "ID123456",
            "status": "active",
            "rating": "4.50",
            "total_trips": 150,
            "photo": null
        },
        "token": "1|abcdefgh..."
    }
}
```

**User Type Responses:**

**For Driver:**
```json
{
    "user_type": "driver",
    "profile": {
        "driver_id": 5,
        "phone": "+966123456789",
        "license_number": "LIC123456",
        "license_expiry": "2026-12-31",
        "id_number": "ID123456",
        "status": "active",
        "rating": "4.50",
        "total_trips": 150,
        "photo": null
    }
}
```

**For Agent:**
```json
{
    "user_type": "agent",
    "profile": {
        "agent_id": 3,
        "phone": "+966123456789",
        "company_name": "ABC Travel Agency",
        "status": "active",
        "commission_type": "percentage",
        "commission_value": "10.00",
        "credit_limit": "50000.00",
        "credit_used": "15000.00",
        "available_budget": 35000.00
    }
}
```

**For Admin:**
```json
{
    "user_type": "admin",
    "profile": {
        "role": "admin",
        "permissions": "full_access"
    }
}
```

**Error Response (401 Unauthorized):**
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

**Error Response (403 Forbidden):**
```json
{
    "success": false,
    "message": "User role not found. Please contact administrator."
}
```

---

### 2. Register
**Endpoint:** `POST /auth/register`

**Description:** Register a new driver or agent account. Admin accounts cannot be created via API.

**Request Body for Driver:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+966123456789",
    "user_type": "driver",
    "device_name": "Android App v1.0",
    "license_number": "LIC123456",
    "license_expiry": "2026-12-31",
    "id_number": "ID123456"
}
```

**Request Body for Agent:**
```json
{
    "name": "Jane Smith",
    "email": "jane@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+966123456789",
    "user_type": "agent",
    "device_name": "Android App v1.0",
    "company_name": "ABC Travel Agency"
}
```

**Success Response (201 Created):**
```json
{
    "success": true,
    "message": "Registration successful. Your account is pending approval.",
    "data": {
        "user": {
            "id": 10,
            "name": "John Doe",
            "email": "john@example.com",
            "user_type": "driver"
        },
        "profile": {
            "driver_id": 8,
            "license_number": "LIC123456",
            "license_expiry": "2026-12-31",
            "status": "pending"
        },
        "token": "2|abcdefgh..."
    }
}
```

**Validation Error Response (422 Unprocessable Entity):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

---

## Protected Endpoints (Require Authentication)

### 3. Get Profile
**Endpoint:** `GET /auth/profile`

**Description:** Get current authenticated user's profile information.

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "user_type": "driver"
        },
        "profile": {
            "driver_id": 5,
            "phone": "+966123456789",
            "license_number": "LIC123456",
            "license_expiry": "2026-12-31",
            "id_number": "ID123456",
            "status": "active",
            "rating": "4.50",
            "total_trips": 150,
            "photo": null
        }
    }
}
```

**Error Response (401 Unauthorized):**
```json
{
    "message": "Unauthenticated."
}
```

---

### 4. Logout
**Endpoint:** `POST /auth/logout`

**Description:** Logout from current device (revoke current access token).

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200 OK):**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

---

### 5. Logout from All Devices
**Endpoint:** `POST /auth/logout-all`

**Description:** Logout from all devices (revoke all access tokens for the user).

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200 OK):**
```json
{
    "success": true,
    "message": "Logged out from all devices successfully"
}
```

---

## User Types

### Driver
- **user_type:** `"driver"`
- **Abilities:** Can access driver-specific endpoints
- **Profile includes:** driver_id, license info, status, rating, total trips

### Agent
- **user_type:** `"agent"`
- **Abilities:** Can access agent-specific endpoints
- **Profile includes:** agent_id, company name, commission info, credit limit

### Admin
- **user_type:** `"admin"`
- **Abilities:** Full system access
- **Profile includes:** admin role and permissions

---

## Status Codes

- **200 OK** - Request successful
- **201 Created** - Resource created successfully
- **401 Unauthorized** - Invalid or missing authentication token
- **403 Forbidden** - User doesn't have required role
- **422 Unprocessable Entity** - Validation error
- **500 Internal Server Error** - Server error

---

## Error Response Format

All error responses follow this format:
```json
{
    "success": false,
    "message": "Error description",
    "error": "Detailed error message (only in development)",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

---

## Authentication Flow

### For Login:
1. User enters email and password in Android app
2. App sends POST request to `/api/auth/login` with credentials
3. API validates credentials and detects user type (driver/agent/admin)
4. API returns user data, profile data, and access token
5. App stores the token and user type locally
6. App redirects user to appropriate screen based on user_type

### For Registration:
1. User fills registration form in Android app
2. App determines user_type (driver or agent)
3. App sends POST request to `/api/auth/register` with required fields
4. API creates user account and profile (status: pending)
5. API returns user data, profile data, and access token
6. App stores the token and notifies user that account is pending approval

---

## Example Code (Android - Kotlin)

### Login Example:
```kotlin
data class LoginRequest(
    val email: String,
    val password: String,
    val device_name: String = "Android App"
)

data class LoginResponse(
    val success: Boolean,
    val message: String,
    val data: LoginData?
)

data class LoginData(
    val user: User,
    val profile: JsonObject,
    val token: String
)

data class User(
    val id: Int,
    val name: String,
    val email: String,
    val user_type: String
)

// API Call
suspend fun login(email: String, password: String): LoginResponse {
    val request = LoginRequest(email, password)
    return apiService.login(request)
}

// Usage
lifecycleScope.launch {
    try {
        val response = login(email, password)
        if (response.success && response.data != null) {
            // Store token
            sharedPrefs.edit().putString("token", response.data.token).apply()
            sharedPrefs.edit().putString("user_type", response.data.user.user_type).apply()
            
            // Navigate based on user type
            when (response.data.user.user_type) {
                "driver" -> navigateToDriverDashboard()
                "agent" -> navigateToAgentDashboard()
                "admin" -> navigateToAdminDashboard()
            }
        }
    } catch (e: Exception) {
        showError(e.message)
    }
}
```

### API Service Interface:
```kotlin
interface ApiService {
    @POST("auth/login")
    suspend fun login(@Body request: LoginRequest): LoginResponse
    
    @POST("auth/register")
    suspend fun register(@Body request: RegisterRequest): LoginResponse
    
    @GET("auth/profile")
    suspend fun getProfile(@Header("Authorization") token: String): ProfileResponse
    
    @POST("auth/logout")
    suspend fun logout(@Header("Authorization") token: String): LogoutResponse
}
```

---

## Testing

You can test the API using Postman or curl:

### Login with curl:
```bash
curl -X POST https://your-domain.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "driver@example.com",
    "password": "password123",
    "device_name": "Android Test"
  }'
```

### Get Profile with curl:
```bash
curl -X GET https://your-domain.com/api/auth/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Notes for Android Developer

1. **Store the token securely** using EncryptedSharedPreferences or DataStore
2. **Store the user_type** to determine which features to show
3. **Handle token expiration** by catching 401 errors and redirecting to login
4. **Include the token** in all protected API calls using an Interceptor
5. **Check user_type** before accessing type-specific features
6. **Handle pending status** for newly registered users
7. **Implement refresh mechanism** for profile data

---

## Security Notes

- All API endpoints use HTTPS only
- Tokens are valid until explicitly revoked
- Passwords are hashed using bcrypt
- API uses Laravel Sanctum for token management
- Rate limiting is applied to prevent abuse
