# 🔐 SecureAuth — PHP Login System

A secure, multi-version PHP login and registration system built with **PHP**, **MySQL**, and **WAMP Server**.  
Developed as part of **COMP-351 Web Engineering** assignments at **Pak-Austria Fachhochschule: Institute of Applied Sciences and Technology, Haripur, Pakistan**.

**Student:** Azeem Mohamed Husain · B22F0759CS142 · CS Fall22 (Blue)  
**Instructor:** Mr. Syed Adil Ibrar Kazmi

---

## 📁 Project Structure

```
secureauth-php-login/
│
├── assignment1/         # Version 1 — Basic Login UI
│   ├── login.php
│   ├── db.php
│   ├── dashboard.php
│   ├── logout.php
│   └── style.css
│
├── assignment2/         # Version 2 — Login + Registration
│   ├── login.php
│   ├── db.php
│   ├── dashboard.php
│   ├── logout.php
│   └── style.css
│
└── assignment3/         # Version 3 — Full Secure Auth + Polished UI/UX
    ├── login.php        # Handles both login and register (tabbed)
    ├── db.php           # MySQL database connection
    ├── dashboard.php    # Protected user dashboard
    ├── logout.php       # Session destroy + redirect
    └── style.css        # Full light/dark theme styling
```

---

## 🚀 Versions Overview

### Version 1 — `assignment1/` · Basic Login
- Simple login form with PHP session handling
- MySQL database connection via `db.php`
- Basic form validation (username & password)
- Redirect to dashboard on successful login
- Protected dashboard page (session check)
- Clean logout functionality

---

### Version 2 — `assignment2/` · Login + Registration
- All features from Version 1
- Added **user registration** form (separate page)
- Password minimum length validation (6+ characters)
- Email format validation using `filter_var()`
- Duplicate username/email check before inserting
- Passwords stored using `password_hash()` (bcrypt)
- Login verifies password using `password_verify()`

---

### Version 3 — `assignment3/` · Polished Secure Auth + UI/UX
- All features from Version 2
- **Single-page tabbed UI** — Login and Register in one file (`login.php`) with smooth tab switching
- **Light / Dark theme toggle** with localStorage persistence across pages
- **Password visibility toggle** (show/hide eye icon)
- Animated background shapes (floating blobs)
- Smooth slide-up card animation on page load
- Themed alert messages (error = red, success = green) with shake animation
- Fully responsive layout (mobile-friendly)
- Dashboard restores user's saved theme preference
- Font Awesome icons on all input fields
- Google Fonts (Poppins) typography

---

## 🔒 Security Features

| Feature | Description |
|---|---|
| `password_hash()` | Passwords hashed with bcrypt (PASSWORD_DEFAULT) |
| `password_verify()` | Secure password comparison — never stores or compares plain text |
| Prepared Statements | All SQL queries use `prepare()` + `bind_param()` — prevents SQL injection |
| `htmlspecialchars()` | All output escaped — prevents XSS attacks |
| Session Protection | Dashboard checks `$_SESSION["user_id"]` — unauthenticated users redirected |
| `session_destroy()` | Full session cleanup on logout |
| Duplicate Check | Username and email uniqueness enforced before registration |
| Email Validation | `filter_var()` with `FILTER_VALIDATE_EMAIL` used server-side |

---

## 🗄️ Database Setup (WAMP Server — Manual)

> No terminal or git commands needed. Follow these steps in your browser.

### Step 1 — Start WAMP
Open WAMP Server and make sure both **Apache** and **MySQL** are running (icon turns green).

### Step 2 — Open phpMyAdmin
Go to: `http://localhost/phpmyadmin`

### Step 3 — Create the Database
1. Click **"New"** on the left sidebar
2. Enter database name: `secure_login`
3. Click **"Create"**

### Step 4 — Create the Users Table
1. Select the `secure_login` database
2. Click the **"SQL"** tab
3. Paste and run the following:

```sql
CREATE TABLE users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    email    VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
```

---

## ⚙️ Installation & Setup (Manual — No Git Commands)

### Step 1 — Copy Project Files
Copy any version folder (e.g. `assignment3`) into your WAMP web root:

```
C:\wamp64\www\secureauth\
```

So the path looks like:
```
C:\wamp64\www\secureauth\login.php
C:\wamp64\www\secureauth\db.php
...
```

### Step 2 — Configure Database Connection
Open `db.php` and update the credentials to match your WAMP setup:

```php
$host   = "localhost";
$user   = "root";        // WAMP default
$pass   = "";            // WAMP default (leave blank) or your password
$dbname = "secure_login";
```

> ⚠️ Do **not** commit `db.php` with your real credentials. See `.gitignore` below.

### Step 3 — Run the App
Open your browser and go to:

```
http://localhost/secureauth/login.php
```

---

## 📸 Pages

| Page | URL | Description |
|---|---|---|
| Login / Register | `/login.php` | Tabbed auth form (v3) or separate pages (v1, v2) |
| Dashboard | `/dashboard.php` | Protected page shown after login |
| Logout | `/logout.php` | Destroys session and redirects to login |

---

## 🛠️ Tech Stack

| Technology | Purpose |
|---|---|
| PHP 7/8 | Backend logic, session management |
| MySQL | User data storage |
| WAMP Server | Local development environment (Apache + MySQL + PHP) |
| HTML5 / CSS3 | Frontend structure and styling |
| Vanilla JavaScript | Tab switching, theme toggle, password visibility |
| Font Awesome 6 | Input icons and logout icon |
| Google Fonts (Poppins) | Typography |

---

## 📋 Requirements

- **WAMP Server** (v3.x or later) — [Download here](https://www.wampserver.com/)
- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher
- A modern web browser (Chrome, Firefox, Edge)

---

## 📝 Notes

- This project is for **educational purposes** as part of coursework.
- All three versions share the same database (`secure_login`) and table (`users`).
- Each version folder is self-contained — you can run them independently.
- WAMP default credentials are `root` with an empty password; update `db.php` if yours differ.

---

## 👤 Author

**Azeem Mohamed Husain**  
Student ID: B22F0759CS142  
Department of IT & Computer Science  
Pak-Austria Fachhochschule, Haripur, Pakistan
