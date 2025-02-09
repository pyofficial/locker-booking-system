# **Project Setup Guide**

## **Steps to Start Project**

```bash
git clone https://github.com/pyofficial/locker-booking-system.git
```

1. **Setup `.env` file**
   - Configure database credentials in `.env`
   - Example:
     ```env
     DB_DATABASE=your_db_name
     DB_USERNAME=your_db_user
     DB_PASSWORD=your_db_password
     ```

2. **Run migrations and seed the database**
   ```bash
   php artisan migrate:fresh --seed
   ```

---

## **API Overview**

For now, **all three open APIs are created** without authentication.

---

## **Migrations Created**

### **Tables**
- `users`
- `locker_stations`
- `lockers`
- `time_slots`
- `booking_schedules`  
  _(Duplicate booking requests are handled in code, but we could also add a partial unique index. However, it's not the best approach, so checks & balances are added in code.)_

### **View Migration**
- `availability_slots` _(Virtual table / view migration)_

---

## **Files in Use**

### **Routes**
- `routes/api.php`

### **Request Validation File**
- `App\Http\Requests\LockerBookingRequest.php`

### **Controller File**
- `App\Http\Controllers\LockerBookingController.php`

### **Repository File**
- `App\Repositories\LockerBookingsRepository.php`

### **Resource Used for Booking Creation**
- `App\Http\Resources\LockerBookingResource.php`

---

## **Important Notes**

- Currently, JSON responses are added in each repository method, but a **common response trait** can be implemented to reduce redundancy.
- While checking **booking availability**, pagination is **not used**. Instead, data is grouped by date for better display in a single view.

---

## **Migrate & Seed the DB**

```bash
php artisan migrate:fresh --seed
```

---

# **API Endpoints & Responses**

## **1. Check Available Lockers**

**Method:** `GET`  
**URL:**  
```plaintext
http://127.0.0.1:8000/api/lockers/available-lockers?locker_station_id=10&locker_id=1&sizes=S,M&date=2025-02-09
```

### **Query Parameters & Validation**
| Parameter           | Type     | Required | Validation |
|--------------------|----------|----------|------------|
| `locker_station_id` | Integer  | Optional | Must exist in `locker_stations` table & not deleted |
| `locker_id`        | Integer  | Optional | Must exist in `lockers` table & not deleted |
| `sizes`            | String   | Optional | Must exist in request file (comma-separated values allowed) |
| `date`             | Date     | Required | Must be within 15 days from the current date |

---

## **2. Create a Booking**

**Method:** `POST`  
**URL:**  
```plaintext
http://127.0.0.1:8000/api/lockers/book-locker
```

### **Request JSON:**
```json
{
    "locker_station_id": 1,
    "locker_id": 1,
    "user_id": 1,
    "time_slot_id": 1,
    "date": "2025-02-12"
}
```

### **Success Response JSON:**
```json
{
    "success": true,
    "data": {
        "locker_station_id": 1,
        "locker_station_name": "Locker Station 1",
        "locker_id": 1,
        "locker_size": null,
        "user_id": 1,
        "user_name": "John Doe",
        "time_slot_id": 1,
        "booking_start_time": "01:00:00",
        "booking_end_time": "03:00:00"
    },
    "message": "Locker booked Successfully."
}
```

---

## **3. Cancel a Booking**

**Method:** `DELETE`  
**URL:**  
```plaintext
http://127.0.0.1:8000/api/lockers/cancel-booking/1
```

### **Response Scenarios**

#### **1️⃣ Resource Not Found**
```json
{
    "success": false,
    "data": [],
    "message": "Resource not found."
}
```

#### **2️⃣ Successfully Cancelled**
```json
{
    "success": true,
    "data": [],
    "message": "Booking Cancelled Successfully."
}
```

#### **3️⃣ Cancellation Not Allowed (Time Slot Passed)**
```json
{
    "success": false,
    "data": [],
    "message": "Booking cannot be canceled as the time slot has already started or passed."
}
```

---

## ** Final Thoughts**

- **All APIs are currently open (no authentication).**
- **Validation is handled in request files, not in controllers.**
- **Duplicate booking prevention is enforced in code rather than using partial unique indexes.**

**Future Improvements:**
- Implement authentication (JWT or Sanctum).
- Create a common response trait to remove redundancy.
- Add pagination for availability checks.

---

