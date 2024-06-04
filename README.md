<p align="center"><img src="./logo.svg" alt="Bloody Buddy Logo" width="400"></p>

<p align="center">
<!--
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
-->
<a href="./LICENSE"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Bloody Buddy API

A REST API repository for app Bloody Buddy, a blood donor web-based application.

<br>

# API Documentation

- [Bloody Buddy API](#bloody-buddy-api)
- [API Documentation](#api-documentation)
  - [Authentication](#authentication)
    - [Register](#register)
    - [Login](#login)
    - [Logout](#logout)
  - [Application](#application)
    - [Donors](#donors)
      - [Get All Donors](#get-all-donors)
- [License](#license)

## Authentication
### Register

> Register a new account.

Request:
```
POST /api/auth/register

Content-Type: multipart/form-data

Body:
{
    "username": "required|string",
    "email": "required|string|email",
    "password": "required|string|min:6",
}
```

Response:
```
Content-Type: application/json

Body:
{
  "status": 200,
  "message": "User created successfully!",
  "user": {
    "uuid": "string|uuid",
    "email": "string|email",
    "username": "string",
    "created_at": "timestamp"
  }
}
```

### Login

> Login into an existing account.

Request:
```
POST /api/auth/login

Content-Type: multipart/form-data

Body:
{
    "email": "required|string|email",
    "password": "required|string|min:6",
}
```

response:
```
Content-Type: application/json

Body:
{
  "status": 200,
  "message": "Successfully logged in!",
  "user": {
    "uuid": "string|uuid",
    "username": "string",
    "email": "string|email",
    "created_at": "timestamp"
  },
  "authorization": {
    "token": "string",
    "type": "bearer"
  }
}
```

### Logout

> Logout from an existing account.

Request:
```
POST /api/auth/logout

Content-Type: multipart/form-data

Header:
{
    "Authorization": "Bearer <TOKEN>"
}
```

Response:
```
Content-Type: application/json

Body:
{
  "status": 200,
  "message": "Successfully logged out!",
  "user": {
    "uuid": "string|uuid",
    "username": "string",
    "email": "string|email",
    "created_at": "timestamp"
  }
}
```

## Application

### Donors

> All endpoints of donor-related data. The entire application API is auth-guarded, while some of it may not (for public use).

#### Get All Donors

> Get all donor data from an authenticated user.

Request:
```
GET /api/donors

Header:
{
    "Authorization": "Bearer <TOKEN>"
}
```

Response:
```
Content-Type: application/json

Body:
{
  "status": 200,
  "message": "Successfully get donors!",
  "data": [
    {
      "uuid": "string|uuid",
      "nik": "string|max:16",
      "name": "string",
      "dob": "date|date_format:Y-m-d",
      "gender": "string",
      "phone_number": "string|max:16",
      "address": "string",
      "blood_type": "string|max:2",
      "body_mass": "numeric",
      "hemoglobin_level": "numeric",
      "blood_pressure": "string",
      "medical_conditions": "string",
      "created_at": "timestamp",
      "updated_at": "timestamp"
    },
    ...
  ]
}
```

<br>

# License

The Bloody Buddy application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
