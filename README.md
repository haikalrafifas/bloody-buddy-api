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

# API Specification

- [Bloody Buddy API](#bloody-buddy-api)
- [API Specification](#api-specification)
  - [Authentication](#authentication)
    - [Register](#register)
    - [Login](#login)
    - [Refresh Token](#refresh-token)
    - [Logout](#logout)
  - [Application](#application)
    - [Donor Applicants](#donor-applicants)
      - [Get All Donor Applicants](#get-all-donor-applicants)
      - [Add a Donor Applicant Data](#add-a-donor-applicant-data)
      - [Change a Donor Applicant Status](#change-a-donor-applicant-status)
    - [Schedules](#schedules)
      - [Get All Donor Schedules](#get-all-donor-schedules)
- [License](#license)

## Authentication
### Register

Request:
  - Method: POST
  - Endpoint: `/api/auth/register`
  - Header:
    - Accept: application/json
  - Body:

```json
{
    "username": "required|string",
    "email": "required|string|email",
    "password": "required|string|min:6",
}
```

Response:
```json
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

Request:
  - Method: POST
  - Endpoint: `/api/auth/login`
  - Header:
    - Accept: application/json
  - Body:
```json
{
    "email": "required|string|email",
    "password": "required|string|min:6",
}
```

Response:
```json
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

### Refresh Token

Request:
  - Method: POST
  - Endpoint: `/api/auth/refresh`
  - Header:
    - Accept: application/json
    - Authorization: Bearer TOKEN
  
Response:
```json
{
  "status": 200,
  "message": "Successfully refresh token!",
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

Request:
  - Method: POST
  - Endpoint: `/api/auth/logout`
  - Header:
    - Accept: application/json
    - Authorization: Bearer TOKEN

Response:
```json
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

> The entire application API is auth-guarded, while some of it may not (for public use).

### Donor Applicants

#### Get All Donor Applicants

> Get all donor data from an authenticated user.

> Admins can see a list of an entire donor applicants, whereas regular user can only see their own submitted donor form.

Request:
  - Method: GET
  - Endpoint: `/api/donors`
  - Header:
    - Accept: application/json
    - Authorization: Bearer TOKEN

Response:
```json
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


#### Add a Donor Applicant Data

> Users can input their donor form here

Request:
  - Method: POST
  - Endpoint: `/api/donor-applicants`
  - Header:
    - Accept: application/json
    - Authorization: Bearer TOKEN
  - Body:
```json
{
  "name": "required|string",
  "nik": "required|string|min:16|max:16",
  "dob": "required|date|date_format:Y-m-d",
  "phone_number": "required|string",
  "address": "required|string",
  "body_mass": "required|numeric",
  "hemoglobin_level": "required|string",
  "blood_type": "required|string|max:2",
  "blood_pressure": "required|string|regex:/^\d+\/\d+$/",
  "medical_conditions": "string",
  "schedule_uuid": "required|string", 
}
```

Response:
```json
{
  "status": 200,
  "message": "Successfully add donor applicant data!",
  "data": {
    "uuid": "string",
    "user": {
      "uuid": "string",
      "username": "string",
      "email": "string"
    },
    "schedule": {
      "uuid": "string",
      "location": {
        "uuid": "string",
        "name": "string",
        "address": "string",
        "image": "string"
      },
      "current_daily_quota": "integer",
      "total_daily_quota": "integer",
      "start_date": "datetime",
      "end_date": "datetime",
      "created_at": "timestamp"
    },
    "status": {
      "name": "string",
      "description": "string"
    },
    "nik": "string|min:16|max:16",
    "name": "string",
    "dob": "date",
    "gender": "string|max:1",
    "phone_number": "string",
    "address": "string",
    "blood_type": "string",
    "body_mass": "integer",
    "hemoglobin_level": "integer",
    "blood_pressure": "string",
    "medical_conditions": "string",
    "created_at": "timestamp"
  }
}
```



#### Change a Donor Applicant Status

> Only admins are allowed to do this.

> Donor Statuses: 'Waiting List', 'Approved', 'Ongoing', 'Done', 'Rejected', 'Cancelled'

```
The valid status change actions:
  'approve' -> Change from Waiting List to Approved

  'reject' -> Change from Waiting List to Rejected
'cancel-apply' -> Change from Waiting List to Cancelled

  'start' -> Change from Approved to Ongoing
  
  'cancel-approval' -> Change from Approved to Cancelled

  'done' -> Change from Ongoing to Done
```

Request:
  - Method: POST
  - Endpoint: `/api/donor-applicant/{uuid}`
  - Header:
    - Accept: application/json
    - Authorization: Bearer TOKEN
  - Body:
```json
{

}
```

Response:
```json
{
  "status": 200,
  "message": "Successfully change donor applicant status: {status}",
  "data": {
    "uuid": "string",
    "user": {
      "uuid": "string",
      "username": "string",
      "email": "string"
    },
    "schedule": {
      "uuid": "string",
      "location": {
        "uuid": "string",
        "name": "string",
        "address": "string",
        "image": "string"
      },
      "current_daily_quota": "integer",
      "total_daily_quota": "integer",
      "start_date": "datetime",
      "end_date": "datetime",
      "created_at": "timestamp"
    },
    "status": {
      "name": "string",
      "description": "string"
    },
    "nik": "string",
    "name": "string",
    "dob": "date|date_format:Y-m-d",
    "gender": "string|max:1",
    "phone_number": "string",
    "address": "string",
    "blood_type": "string|max:2",
    "body_mass": "integer",
    "hemoglobin_level": "integer",
    "blood_pressure": "string",
    "medical_conditions": "string",
    "created_at": "timestamp"
  }
}
```

<br>

### Schedules

#### Get All Donor Schedules

> Get all donor shcedules.

> Unlike any other endpoint, this one can be publicly accessed, so it does not need authentication header.

Request:
  - Method: GET
  - Endpoint: `/api/schedules`
  - Header:
    - Accept: application/json

Response:
```json
{
  "status": 200,
  "message": "Successfully get schedules!",
  "data": [
    {
      "uuid": "string",
      "location": {
        "uuid": "string",
        "name": "string",
        "address": "text",
        "image": "string",
        "created_at": "timestamp",
        "updated_at": "timestamp",
        "deleted_at": "timestamp",
      },
      "daily_quota": "integer",
      "current_daily_quota": "integer",
      "start_date": "date|date_format:Y-m-d",
      "end_date": "date|date_format:Y-m-d"
    },
    ...
  ]
}
```

<br>



<br>

# License

The Bloody Buddy application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
