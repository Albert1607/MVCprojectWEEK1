# MediCare Clinic - Pure PHP MVC Project

An educational, cleanly structured **Doctor & Clinic Appointment Booking Portal** built from scratch using **Pure/Vanilla PHP (OOP & PDO)** to demonstrate the **Model-View-Controller (MVC)** architectural design pattern.

---

## 🌟 Architectural Overview: How MVC Works Here

```
                            HTTP Request (e.g., GET /appointments)
                                              │
                                              ▼
                             public/index.php (Front Controller)
                                              │
                                              ▼
                                   core/Router.php
                                              │
                                              ▼
                         app/Controllers/AppointmentController.php
                                    │                   │
                     (Query / Save) │                   │ (Render with data)
                                    ▼                   ▼
                           app/Models/Appointment.php   app/Views/appointments/index.php
                                    │                   │
                                    ▼                   ▼
                             SQLite / MySQL DB     HTML Sent to Browser
```

### 1. The Model Layer (`app/Models/`)
* **Role:** Manages business logic, database queries, validations, and state.
* **Files:**
  * `BaseModel.php`: Connects to PDO database via `Database::getConnection()`.
  * `Doctor.php`: Queries doctor profiles, specialties, and schedules.
  * `Patient.php`: Finds existing patients or creates new records on the fly.
  * `Appointment.php`: Handles appointments and business rules—notably **preventing double-booking** via `isSlotAvailable($doctorId, $date, $time)`.

### 2. The View Layer (`app/Views/`)
* **Role:** The presentation layer responsible for rendering HTML to the user.
* **Important MVC Rule:** Views **never** query the database directly. They only receive pure PHP arrays/variables passed down from the Controller.
* **Files:**
  * `layouts/header.php` & `layouts/footer.php`: Reusable modern UI layout and navigation.
  * `home/index.php`: Clinic dashboard with statistics cards and active appointments.
  * `doctors/index.php` & `doctors/show.php`: Doctor directory with specialty filter tags and doctor profile schedules.
  * `appointments/index.php` & `appointments/create.php`: Appointments management table and interactive booking form.

### 3. The Controller Layer (`app/Controllers/`)
* **Role:** The brain/traffic coordinator. Intercepts the request, asks the Model for data, applies application logic, and decides which View to render.
* **Files:**
  * `BaseController.php`: Provides `$this->view('template/name', $data)`, `$this->redirect($url)`, and flash messaging (`$this->setFlash(...)`).
  * `HomeController.php`: Fetches clinic statistics and recent visits for the home dashboard.
  * `DoctorController.php`: Coordinates doctor listing and profile views.
  * `AppointmentController.php`: Manages the full booking workflow, input validation, conflict checks, cancellation, and completion.

### 4. The Core & Front Controller (`core/` & `public/`)
* `public/index.php`: **The Single Entry Point** (Front Controller). Every request begins here. It sets up autoloading, registers routes, and asks the Router to dispatch.
* `core/Request.php`: Wraps `$_SERVER`, `$_POST`, and `$_GET` into clean, sanitized methods (`getMethod()`, `getPath()`, `input()`).
* `core/Router.php`: Maps HTTP verbs (`GET`, `POST`) and URL patterns (including parameters like `/doctors/{id}`) to Controller actions.

---

## 📁 Directory Structure

```
MVCprojectWEEK1/
├── app/
│   ├── Config/
│   │   └── Database.php             # PDO connection (SQLite default, MySQL ready)
│   ├── Controllers/
│   │   ├── BaseController.php       # View rendering & flash helpers
│   │   ├── HomeController.php       # Dashboard controller
│   │   ├── DoctorController.php     # Doctor directory controller
│   │   └── AppointmentController.php# Booking & status controller
│   ├── Models/
│   │   ├── BaseModel.php            # Base PDO database wrapper
│   │   ├── Doctor.php               # Doctor database model
│   │   ├── Patient.php              # Patient database model
│   │   └── Appointment.php          # Appointment model + conflict logic
│   └── Views/
│       ├── layouts/
│       │   ├── header.php           # Common HTML header & navbar
│       │   └── footer.php           # Common HTML footer
│       ├── home/
│       │   └── index.php            # Dashboard template
│       ├── doctors/
│       │   ├── index.php            # Doctor cards template
│       │   └── show.php             # Doctor profile & schedule
│       └── appointments/
│           ├── index.php            # Appointment list & status filter
│           └── create.php           # Booking form template
├── core/
│   ├── Request.php                  # HTTP Request object
│   └── Router.php                   # Regex URL router & dispatcher
├── database/
│   ├── schema.sql                   # SQL table definitions
│   ├── seed.php                     # Seeder script for sample data
│   └── clinic.sqlite                # Auto-generated SQLite database
├── public/
│   ├── css/
│   │   └── style.css                # Custom medical UI styling
│   ├── .htaccess                    # Apache URL rewrite rules
│   └── index.php                    # Front Controller (Entry point)
├── README.md                        # Documentation
└── run.bat                          # One-click Windows launcher
```

---

## 🚀 How to Run the Project

### Method 1: Using the Quick Launcher (`run.bat`)
Double-click `run.bat` in the project root folder. It will:
1. Initialize the SQLite database and seed test data.
2. Launch PHP's built-in web server at `http://localhost:8000`.
3. Open your default browser to `http://localhost:8000`.

### Method 2: Command Line (PowerShell / Command Prompt)
Run the following commands using your PHP binary (e.g. XAMPP PHP):

```powershell
# 1. Seed database (creates database/clinic.sqlite)
& "C:\xampp\php\php.exe" database\seed.php

# 2. Start the development server
& "C:\xampp\php\php.exe" -S localhost:8000 -t public
```

Now open your web browser to: **`http://localhost:8000`**

---

## 🧪 Testing the MVC Flow & Business Rules

1. **View Clinic Dashboard (`/`):**
   - See summary KPI counters: Total Doctors, Upcoming Scheduled visits, Completed visits, and Registered Patients.
2. **Browse Doctors (`/doctors`):**
   - Filter by specialty (Cardiology, Dermatology, Pediatrics, General Practice, Neurology).
   - Click **Profile** on any doctor to view their bio and active reservations.
3. **Book an Appointment (`/appointments/create`):**
   - Choose a doctor, date, time slot, and enter patient details.
   - Click **Confirm & Book Appointment**.
   - You will be redirected to `/appointments` with a green success notification!
4. **Test Double-Booking Prevention (Business Rule):**
   - Try booking an appointment with the **same doctor** on the **same date** and at the **same time slot** as an existing booking.
   - The `Appointment` model flags the conflict, and the `AppointmentController` redirects back with an error alert:
     > *"Sorry, Dr. ... already has an active appointment on ... at .... Please pick a different time slot."*
5. **Manage Appointment Statuses (`/appointments`):**
   - Click **Complete** to mark an appointment finished.
   - Click **Cancel** to cancel an appointment (which immediately frees the doctor's time slot for new bookings!).