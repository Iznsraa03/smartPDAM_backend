# Smart PDAM API Documentation

This document outlines the available REST API endpoints for the Smart PDAM platform. 

**Base URL:** `http://localhost:8000/api/v1`

## Authentication

All authenticated requests must include the `Authorization: Bearer {token}` header.

### 1. Register User
`POST /auth/register`

**Request Body:**
```json
{
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "phone": "081234567890",
  "address": "Jl. Sudirman No. 45, Jakarta Selatan",
  "latitude": -6.20880,
  "longitude": 106.84560,
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Success Response (201 Created):**
```json
{
  "message": "Registration successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Budi Santoso",
      "email": "budi@example.com",
      "phone": "081234567890",
      "address": "Jl. Sudirman No. 45, Jakarta Selatan",
      "latitude": -6.20880,
      "longitude": 106.84560,
      "role": "customer",
      "status": "active"
    },
    "token": "1|abcdef1234567890"
  }
}
```

### 2. Login
`POST /auth/login`

**Request Body:**
```json
{
  "email": "budi@example.com",
  "password": "password123",
  "device_name": "iPhone 13"
}
```

**Success Response (200 OK):**
```json
{
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Budi Santoso",
      "role": "customer"
    },
    "token": "2|abcdef1234567890"
  }
}
```

### 3. Logout
`POST /auth/logout` *(Requires Auth)*

**Success Response (200 OK):**
```json
{
  "message": "Logged out successfully"
}
```

---

## Customer Profile

### 1. Get Profile
`GET /profile` *(Requires Auth)*

**Success Response (200 OK):**
```json
{
  "data": {
    "id": 1,
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "phone": "081234567890",
    "status": "active",
    "joined_at": "2024-01-01T10:00:00.000000Z"
  }
}
```

---

## Meter Readings (Catat Meter Mandiri)

### 1. Submit Meter Reading
`POST /meter-readings` *(Requires Auth)*

> [!NOTE]
> This endpoint supports `multipart/form-data` for file uploads.

**Form Data:**
- `water_meter_id`: 1
- `reading_value`: 1205
- `photo_path`: (File Upload)
- `reading_date`: 2024-05-15

**Success Response (201 Created):**
```json
{
  "message": "Meter reading submitted successfully.",
  "data": {
    "id": 10,
    "water_meter_id": 1,
    "reading_value": 1205,
    "status": "pending",
    "photo_path": "meter_readings/xxx.jpg",
    "reading_date": "2024-05-15"
  }
}
```

---

## Invoices (Tagihan)

### 1. List Invoices
`GET /invoices` *(Requires Auth)*

**Success Response (200 OK):**
```json
{
  "data": [
    {
      "id": 100,
      "invoice_number": "INV-202405-001",
      "billing_period": "2024-05",
      "total_amount": 125000,
      "status": "unpaid",
      "due_date": "2024-05-20"
    }
  ]
}
```

---

## Payments

### 1. Create Payment (Midtrans Snap)
`POST /payments/invoices/{invoice_id}/create` *(Requires Auth)*

**Success Response (200 OK):**
```json
{
  "message": "Payment token generated",
  "data": {
    "snap_token": "a1b2c3d4-e5f6-7890",
    "redirect_url": "https://app.sandbox.midtrans.com/snap/v2/vtweb/a1b2c3d4..."
  }
}
```

### 2. Midtrans Webhook (Internal)
`POST /payments/webhook`

**Request Body (From Midtrans):**
```json
{
  "transaction_time": "2024-05-15 10:11:12",
  "transaction_status": "settlement",
  "transaction_id": "93c6a5...",
  "status_message": "midtrans payment notification",
  "status_code": "200",
  "signature_key": "xxx...",
  "payment_type": "gopay",
  "order_id": "INV-202405-001",
  "merchant_id": "G123456",
  "gross_amount": "125000.00",
  "fraud_status": "accept",
  "currency": "IDR"
}
```

**Success Response (200 OK):**
```json
{
  "message": "Webhook processed successfully"
}
```

---

## Admin APIs

Admin endpoints are restricted to users with the `admin` or `super_admin` role.

### 1. Approve Meter Reading
`PATCH /admin/meter-readings/{id}/approve` *(Requires Admin)*

**Request Body:**
```json
{
  "notes": "Reading looks accurate."
}
```

**Success Response (200 OK):**
```json
{
  "message": "Meter reading approved. Invoice generated.",
  "data": {
    "id": 10,
    "status": "approved",
    "invoice": {
      "invoice_number": "INV-202405-002",
      "total_amount": 125000
    }
  }
}
```

### 2. Reject Meter Reading
`PATCH /admin/meter-readings/{id}/reject` *(Requires Admin)*

**Request Body:**
```json
{
  "rejection_reason": "Photo is too blurry, please retake."
}
```

**Success Response (200 OK):**
```json
{
  "message": "Meter reading rejected.",
  "data": {
    "id": 10,
    "status": "rejected",
    "rejection_reason": "Photo is too blurry, please retake."
  }
}
```
