# Contact Management System - Backend API
## PHP + MySQL REST API

<img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" /> <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" /> <img src="https://img.shields.io/badge/REST%20API-02569B?style=for-the-badge&logo=rest&logoColor=white" />

---

## 📋 Project Overview

This is a **RESTful API backend** for a Contact Management System built with **PHP and MySQL**. The API provides 5 endpoints for full CRUD operations (Create, Read, Update, Delete) on contact records.

**Base URL:** `https://apps.ashesi.edu.gh/contactmgt/actions/`

---

## 🏗️ Project Structure

```
Mobile_App_Lab/
├── actions/                           # API endpoint files
│   ├── get_all_contact_mob.php       # Get all contacts
│   ├── add_contact_mob.php           # Add new contact
│   ├── get_a_contact_mob.php         # Get single contact
│   ├── update_contact.php            # Update contact
│   └── delete_contact.php            # Delete contact
│
├── database/                          # Database configuration
│   ├── schema.sql                    # Database schema & sample data
│   ├── db_connection.php             # Database connection functions
│   └── config.example.php            # Configuration template
│
├── tests/                             # Testing files
│   ├── test_api.html                 # Browser-based API tester
│   └── postman_collection.json       # Postman collection
│
└── README.md                          # This file
```

---

## 📊 Database Schema

### Table: `contacts`

| Column      | Type         | Description                  |
|-------------|--------------|------------------------------|
| `pid`       | INT(11)      | Primary key (auto-increment) |
| `pname`     | VARCHAR(255) | Contact name                 |
| `pphone`    | VARCHAR(20)  | Contact phone number         |
| `created_at`| TIMESTAMP    | Creation timestamp           |
| `updated_at`| TIMESTAMP    | Last update timestamp        |

**SQL Schema:**
```sql
CREATE TABLE contacts (
    pid INT(11) AUTO_INCREMENT PRIMARY KEY,
    pname VARCHAR(255) NOT NULL,
    pphone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

## 🔌 API Endpoints Specification

### 1️⃣ Get All Contacts

**Endpoint:** `get_all_contact_mob.php`  
**Method:** `GET`  
**Request Data:** None

**Response:**
```json
[
  {
    "pid": "1",
    "pname": "John Doe",
    "pphone": "+233501234567"
  },
  {
    "pid": "2",
    "pname": "Jane Smith",
    "pphone": "+233241234567"
  }
]
```

**CURL Example:**
```bash
curl -X GET https://apps.ashesi.edu.gh/contactmgt/actions/get_all_contact_mob.php
```

---

### 2️⃣ Add New Contact

**Endpoint:** `add_contact_mob.php`  
**Method:** `POST`  
**Request Data:**
```json
{
  "ufullname": "John Doe",
  "uphonename": "+233501234567"
}
```

**Response:**
```json
"success"
```
or
```json
"failed - [error message]"
```

**CURL Example:**
```bash
curl -X POST https://apps.ashesi.edu.gh/contactmgt/actions/add_contact_mob.php \
  -H "Content-Type: application/json" \
  -d '{"ufullname":"John Doe","uphonename":"+233501234567"}'
```

---

### 3️⃣ Get One Contact

**Endpoint:** `get_a_contact_mob.php`  
**Method:** `GET`  
**Request Data:** `contid` (query parameter)

**Response:**
```json
{
  "pid": "1",
  "pname": "John Doe",
  "pphone": "+233501234567"
}
```

**CURL Example:**
```bash
curl -X GET "https://apps.ashesi.edu.gh/contactmgt/actions/get_a_contact_mob.php?contid=1"
```

---

### 4️⃣ Update Contact

**Endpoint:** `update_contact.php`  
**Method:** `POST`  
**Request Data:**
```json
{
  "cid": 1,
  "cname": "John Doe Updated",
  "cnum": "+233509876543"
}
```

**Response:**
```json
"success"
```
or
```json
"failed - [error message]"
```

**CURL Example:**
```bash
curl -X POST https://apps.ashesi.edu.gh/contactmgt/actions/update_contact.php \
  -H "Content-Type: application/json" \
  -d '{"cid":1,"cname":"John Doe Updated","cnum":"+233509876543"}'
```

---

### 5️⃣ Delete Contact

**Endpoint:** `delete_contact.php`  
**Method:** `POST`  
**Request Data:**
```json
{
  "cid": 1
}
```

**Response:**
```json
true
```
or
```json
false
```

**CURL Example:**
```bash
curl -X POST https://apps.ashesi.edu.gh/contactmgt/actions/delete_contact.php \
  -H "Content-Type: application/json" \
  -d '{"cid":1}'
```

---

## 🚀 Installation & Setup

### Step 1: Configure Database Connection

1. Navigate to `database/` folder
2. Copy `config.example.php` to `config.php`
3. Update database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'contactmgt');
```

4. Update `database/db_connection.php` with your credentials

### Step 2: Create Database

1. Log in to phpMyAdmin or MySQL console
2. Run the SQL script from `database/schema.sql`:

```sql
CREATE DATABASE contactmgt;
USE contactmgt;

-- Run the full schema.sql file
```

3. Verify table creation:
```sql
SHOW TABLES;
DESCRIBE contacts;
```

### Step 3: Upload Files to Server

1. Upload the `actions/` folder to your server
2. Upload the `database/` folder to your server
3. Ensure proper file permissions (644 for PHP files)

**Server Structure:**
```
/var/www/html/contactmgt/
├── actions/
│   ├── get_all_contact_mob.php
│   ├── add_contact_mob.php
│   ├── get_a_contact_mob.php
│   ├── update_contact.php
│   └── delete_contact.php
└── database/
    ├── db_connection.php
    └── config.php
```

### Step 4: Test API Endpoints

Use the provided `tests/test_api.html` file or Postman to test all endpoints.

---

## 🧪 Testing the API

### Option 1: Using Browser (GET requests only)

Test GET endpoints directly in your browser:

```
https://apps.ashesi.edu.gh/contactmgt/actions/get_all_contact_mob.php
https://apps.ashesi.edu.gh/contactmgt/actions/get_a_contact_mob.php?contid=1
```

### Option 2: Using Postman

1. Import `tests/postman_collection.json` into Postman
2. Update the base URL if needed
3. Test all 5 endpoints

### Option 3: Using CURL

Test all endpoints using the CURL commands provided above.

### Option 4: Using HTML Test Page

Open `tests/test_api.html` in your browser for an interactive testing interface.

---

## 🔒 Security Features

✅ **SQL Injection Protection** - Prepared statements used throughout  
✅ **Input Validation** - All inputs validated before processing  
✅ **Error Handling** - Proper error logging and user-friendly messages  
✅ **CORS Enabled** - Cross-Origin Resource Sharing for mobile apps  
✅ **Type Checking** - Data types validated  
✅ **Sanitization** - Inputs trimmed and sanitized  

---

## ✅ Features

- ✅ Full CRUD operations (Create, Read, Update, Delete)
- ✅ RESTful API design
- ✅ JSON request/response format
- ✅ SQL injection protection (prepared statements)
- ✅ Input validation
- ✅ Error handling
- ✅ CORS support for mobile apps
- ✅ Comprehensive documentation
- ✅ Testing tools included

---

## 📱 Mobile App Integration

This API is designed to work with MIT App Inventor and other mobile frameworks.

**Example App Inventor Integration:**

1. **Web Component Setup:**
   - Add `Web1` component
   - Set `Web1.Url` to endpoint URL

2. **GET Request (Get All Contacts):**
   ```
   set Web1.Url to "https://apps.ashesi.edu.gh/contactmgt/actions/get_all_contact_mob.php"
   call Web1.Get
   ```

3. **POST Request (Add Contact):**
   ```
   set Web1.Url to "https://apps.ashesi.edu.gh/contactmgt/actions/add_contact_mob.php"
   call Web1.PostText
     text: {"ufullname":"John","uphonename":"+233501234567"}
   ```

---

## 🐛 Troubleshooting

### Issue: Database Connection Failed

**Solution:**
- Verify database credentials in `db_connection.php`
- Check if MySQL service is running
- Ensure database exists: `CREATE DATABASE contactmgt;`

### Issue: 404 Not Found

**Solution:**
- Verify file paths on server
- Check `.htaccess` configuration
- Ensure files have correct permissions (644)

### Issue: CORS Errors

**Solution:**
- Headers are already set in each PHP file
- If issues persist, check server CORS configuration

### Issue: Empty Response

**Solution:**
- Check database has data
- Enable error reporting: `error_reporting(E_ALL);`
- Check PHP error logs

### Issue: JSON Parse Error

**Solution:**
- Ensure `Content-Type: application/json` header is set
- Validate JSON format before sending
- Check for PHP warnings/errors in response

---

## 📝 Validation Rules

### Contact Name:
- ✅ Required
- ✅ Cannot be empty
- ✅ Maximum 255 characters

### Phone Number:
- ✅ Required
- ✅ Cannot be empty
- ✅ Minimum 10 characters
- ✅ Maximum 20 characters

### Contact ID:
- ✅ Must be positive integer
- ✅ Must exist in database

---

## 🎯 Lab Requirements Checklist

- [x] **5 API Endpoints implemented**
  - [x] Get All Contacts (GET)
  - [x] Add New Contact (POST)
  - [x] Get One Contact (GET)
  - [x] Update Contact (POST)
  - [x] Delete Contact (POST)

- [x] **Database Integration**
  - [x] MySQL database schema
  - [x] Proper table structure
  - [x] Sample data included

- [x] **Code Quality**
  - [x] Prepared statements (SQL injection protection)
  - [x] Input validation
  - [x] Error handling
  - [x] Code comments

- [x] **Documentation**
  - [x] API specification
  - [x] Setup instructions
  - [x] Testing guide
  - [x] CURL examples

---

## 📚 Additional Resources

### Learning Resources:
- [PHP Manual](https://www.php.net/manual/en/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [RESTful API Design](https://restfulapi.net/)
- [HTTP Status Codes](https://httpstatuses.com/)

### Tools:
- [Postman](https://www.postman.com/) - API testing
- [phpMyAdmin](https://www.phpmyadmin.net/) - Database management
- [JSON Formatter](https://jsonformatter.org/) - Validate JSON

---

## 👨‍💻 Development Notes

### HTTP Status Codes Used:
- `200` - Success
- `400` - Bad Request (invalid input)
- `404` - Not Found (resource doesn't exist)
- `405` - Method Not Allowed (wrong HTTP method)
- `500` - Internal Server Error

### Response Formats:
- **Get All Contacts:** JSON array
- **Get One Contact:** JSON object
- **Add Contact:** String ("success"/"failed")
- **Update Contact:** String ("success"/"failed")
- **Delete Contact:** Boolean (true/false)

---

## 🎓 Submission Checklist

Before submitting your lab:

- [ ] All 5 endpoints working correctly
- [ ] Database created and populated
- [ ] All endpoints tested (screenshots taken)
- [ ] Code commented and clean
- [ ] Documentation complete
- [ ] Test results documented

---

## 📞 Support

If you encounter issues:

1. Check the troubleshooting section
2. Review PHP error logs
3. Test with provided CURL commands
4. Verify database connection
5. Check file permissions

---

## 📄 License

This project is for educational purposes as part of Ashesi University Mobile App Development Lab.

---

**Author:** Mobile App Development Lab  
**Version:** 1.0  
**Last Updated:** February 7, 2026

---

## 🎉 You're Ready!

Your Contact Management API is now complete. Test all endpoints and integrate with your mobile app!

**Happy Coding! 🚀**
