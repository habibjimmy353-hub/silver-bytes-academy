# Silver Bytes Academy

A friendly, easy-to-read website for a technology training academy for
elders and older people. Built with **HTML, CSS, JavaScript, PHP and
MySQL**, plus two small optional **Python** helper tools for the admin.

---

## 1. What This Website Can Do

- **Public pages:** Home, About, Courses, Website Policy, Contact.
- **Student enrollment (registration):** Anyone can create a free
  account with full details (name, email, phone, age, address,
  password, and their course of interest).
- **Student login / logout:** Returning students log in with their
  email and password. They do not need to register twice.
- **Student "My Account" page:** Shows the student their own saved
  details.
- **Admin control panel** (separate, secure login):
  - Dashboard with student / course / message counters.
  - See, search, block/unblock, and delete student accounts.
  - Add and delete courses.
  - Read and reply to messages sent from the Contact page.
- **Contact options:** WhatsApp button, email link, and a contact
  form that is saved into the database.
- Beautiful, large-text, high-contrast design made for elder users,
  with gentle animations and a mobile-friendly layout.

---

## 2. Step-by-Step Installation (XAMPP)

This project is a normal PHP website, so it runs perfectly inside
**XAMPP** (a free program that gives your computer Apache, PHP and
MySQL together).

### Step 1 - Install XAMPP
Download XAMPP from `https://www.apachefriends.org` if you do not
already have it, and install it normally.

### Step 2 - Copy the website files
1. Open your XAMPP folder. On Windows this is usually `C:\xampp`.
2. Open the `htdocs` folder inside it (`C:\xampp\htdocs`).
3. Copy the whole `silver-bytes-academy` folder (this folder) into
   `htdocs`, so the path looks like:
   `C:\xampp\htdocs\silver-bytes-academy\`

### Step 3 - Start Apache and MySQL
1. Open the **XAMPP Control Panel**.
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.
   (Both rows should turn green.)

### Step 4 - Create the database
1. In your web browser, go to: `http://localhost/phpmyadmin`
2. Click the **Import** tab at the top.
3. Click **Choose File** and select the file:
   `database/schema.sql` (inside this project folder).
4. Scroll down and click the **Go** button.
5. phpMyAdmin will create a new database called
   `silver_bytes_academy`, with all the tables, sample courses,
   and one starting admin account, all ready to use.

### Step 5 - Open the website
In your browser, go to:

```
http://localhost/silver-bytes-academy/
```

That's it - the website is now running on your computer!

---

## 3. Logging Into the Admin Panel

The admin control panel is at a separate address:

```
http://localhost/silver-bytes-academy/admin/login.php
```

**Starting admin account (created automatically by schema.sql):**

| Field    | Value       |
|----------|-------------|
| Username | `admin`     |
| Password | `Admin@123` |

> ⚠️ **Important:** Please log in and change this password soon.
> The easiest way is to run the Python tool described below and
> update the `password_hash` value for the `admin` row directly in
> phpMyAdmin's **admins** table (there is no "change password" page
> yet, on purpose, to keep the admin panel simple and safe).

---

## 4. Project Folder Structure

```
silver-bytes-academy/
│
├── index.php              Home page
├── about.php               About Us page
├── courses.php             Courses list (reads from database)
├── policy.php               Website Policy page
├── contact.php               Contact page + contact form
├── register.php              Student enrollment (create account)
├── login.php                  Student login
├── logout.php                  Student logout
├── dashboard.php                Student "My Account" page
│
├── admin/
│   ├── login.php                Admin login
│   ├── logout.php                 Admin logout
│   ├── dashboard.php               Admin dashboard (stats)
│   ├── students.php                 Manage students
│   ├── courses.php                    Manage courses
│   ├── messages.php                    Read contact messages
│   └── includes/                        Admin header/footer
│
├── config/
│   └── database.php              Database connection settings
│
├── includes/
│   ├── functions.php               Shared helper functions
│   ├── header.php                    Shared page header/navigation
│   └── footer.php                     Shared page footer
│
├── assets/
│   ├── css/style.css               All the website's styling
│   ├── js/main.js                   Menu, animations, form checks
│   └── images/                       (put extra images here)
│
├── database/
│   └── schema.sql                  Creates the database + tables
│
├── tools/                             Optional Python helper scripts
│   ├── generate_password_hash.py
│   ├── backup_database.py
│   └── requirements.txt
│
└── README.md                          This file
```

---

## 5. Contact Information Used on the Website

- **WhatsApp:** +213 69 73 29 38
- **Email:** wadhaja25597@gmail.com

You can change these at any time by searching for them inside
`includes/footer.php`, `contact.php`, `dashboard.php`, and
`policy.php`, and typing your new details.

---

## 6. Security Notes (Please Read)

This project already includes several important protections:

- **Prepared statements** everywhere a database query is built, to
  stop SQL injection (attackers typing SQL code into a form).
- **Password hashing** with PHP's `password_hash()` /
  `password_verify()` - real passwords are never stored anywhere.
- **CSRF tokens** on every form, to stop other websites from
  submitting forms using a visitor's browser without them knowing.
- **XSS protection** - all text coming from the database or a form
  is passed through `htmlspecialchars()` before being displayed.
- **Login rate-limiting** - after 5 wrong password attempts, the
  login form pauses for 60 seconds.
- **Secure sessions** - session cookies are `HttpOnly` and
  `SameSite=Lax`, and the session ID is refreshed after every login.

Before putting this website on the real internet (not just your own
computer), please also:
1. Change the default admin password.
2. Turn on HTTPS (a padlock icon) on your web host.
3. Change `DB_USER` / `DB_PASS` in `config/database.php` to a
   MySQL account made only for this website (not `root`).

---

## 7. Optional Python Tools

Inside the `tools/` folder:

- **`generate_password_hash.py`** - creates a new secure password
  hash, useful for adding a second admin or resetting a lost
  password by hand in phpMyAdmin.
- **`backup_database.py`** - saves a backup copy of the database to
  a `.sql` file using XAMPP's `mysqldump` tool.

To use them:
```
cd tools
pip install -r requirements.txt
python generate_password_hash.py
```

These tools are optional extras - the website itself works fully
without ever running Python.
