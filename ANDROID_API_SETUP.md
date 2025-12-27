# MOEAN System - Android API Setup Guide

## Overview
This document provides a quick setup guide for integrating the MOEAN authentication API with your Android application.

## API Endpoints

### Base URL
```
Production: https://your-domain.com/api
Development: http://moeen1.test/api
```

### Available Endpoints

#### Public (No Auth Required)
- `POST /auth/login` - User login
- `POST /auth/register` - User registration

#### Protected (Auth Required)
- `GET /auth/profile` - Get user profile
- `POST /auth/logout` - Logout current device
- `POST /auth/logout-all` - Logout all devices

## Authentication System

### User Types Supported
1. **Driver** - For delivery/transport drivers
2. **Agent** - For travel agents/booking agents
3. **Admin** - For system administrators

The API automatically detects the user type during login and returns the appropriate profile data.

## Quick Start

### 1. Login Request
```kotlin
POST /api/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123",
    "device_name": "Android App v1.0"
}
```

### 2. Success Response
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
            // Driver-specific data
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz..."
    }
}
```

### 3. Store Token
Store the token securely and include it in all subsequent API requests:
```
Authorization: Bearer {token}
```

## Android Implementation

### Dependencies (build.gradle)
```gradle
// Retrofit for API calls
implementation 'com.squareup.retrofit2:retrofit:2.9.0'
implementation 'com.squareup.retrofit2:converter-gson:2.9.0'

// OkHttp for interceptors
implementation 'com.squareup.okhttp3:okhttp:4.11.0'
implementation 'com.squareup.okhttp3:logging-interceptor:4.11.0'

// DataStore for secure token storage
implementation 'androidx.datastore:datastore-preferences:1.0.0'

// Coroutines
implementation 'org.jetbrains.kotlinx:kotlinx-coroutines-android:1.7.1'
```

### API Service Interface
```kotlin
interface ApiService {
    @POST("auth/login")
    suspend fun login(@Body request: LoginRequest): Response<LoginResponse>
    
    @POST("auth/register")
    suspend fun register(@Body request: RegisterRequest): Response<LoginResponse>
    
    @GET("auth/profile")
    suspend fun getProfile(): Response<ProfileResponse>
    
    @POST("auth/logout")
    suspend fun logout(): Response<LogoutResponse>
}
```

### Data Classes
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
```

### Auth Interceptor
```kotlin
class AuthInterceptor(private val tokenProvider: () -> String?) : Interceptor {
    override fun intercept(chain: Interceptor.Chain): okhttp3.Response {
        val request = chain.request()
        val token = tokenProvider()
        
        val authenticatedRequest = if (token != null) {
            request.newBuilder()
                .header("Authorization", "Bearer $token")
                .build()
        } else {
            request
        }
        
        return chain.proceed(authenticatedRequest)
    }
}
```

### Retrofit Setup
```kotlin
object RetrofitClient {
    private const val BASE_URL = "https://your-domain.com/api/"
    
    private val okHttpClient = OkHttpClient.Builder()
        .addInterceptor(AuthInterceptor { TokenManager.getToken() })
        .addInterceptor(HttpLoggingInterceptor().apply {
            level = HttpLoggingInterceptor.Level.BODY
        })
        .build()
    
    val apiService: ApiService = Retrofit.Builder()
        .baseUrl(BASE_URL)
        .client(okHttpClient)
        .addConverterFactory(GsonConverterFactory.create())
        .build()
        .create(ApiService::class.java)
}
```

## User Flow

### Login Flow
1. User enters email and password
2. App calls `/api/auth/login`
3. API returns user data with `user_type`
4. App stores token and user_type
5. App navigates to appropriate screen:
   - `driver` → Driver Dashboard
   - `agent` → Agent Dashboard
   - `admin` → Admin Panel

### Registration Flow
1. User selects account type (Driver/Agent)
2. User fills registration form
3. App calls `/api/auth/register` with `user_type`
4. API creates account (status: pending)
5. App stores token
6. App shows "Account pending approval" message

## Security Best Practices

1. **Store tokens securely** using EncryptedSharedPreferences or DataStore
2. **Use HTTPS only** for all API calls
3. **Handle 401 errors** by redirecting to login
4. **Implement token refresh** if needed
5. **Clear tokens** on logout
6. **Validate user_type** before accessing features

## Testing

### Postman Collection
Import `MOEAN_Auth_API.postman_collection.json` into Postman for testing.

### Test Credentials
```
Driver:
Email: driver@example.com
Password: password123

Agent:
Email: agent@example.com
Password: password123
```

## Error Handling

### Common Errors
- `401` - Invalid credentials or expired token
- `403` - User doesn't have required role
- `422` - Validation error
- `500` - Server error

### Example Error Response
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

## Complete Documentation

For detailed API documentation, see `API_DOCUMENTATION.md`

## Support

For API issues or questions, contact the backend team.
