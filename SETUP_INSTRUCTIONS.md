# 🚀 Quick Setup Instructions
## Get Your API Running in 10 Minutes!

---

## Step 1: Get Your Database Credentials

You'll need these from your Ashesi server access:
- **Database Host:** Usually `localhost` or provided by IT
- **Database Username:** Your MySQL username
- **Database Password:** Your MySQL password
- **Database Name:** `contactmgt`

---

## Step 2: Configure Database Connection

### Option A: Edit db_connection.php directly

1. Open `database/db_connection.php`
2. Find these lines (around line 12-15):

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');  // ← CHANGE THIS
define('DB_PASS', 'your_password');  // ← CHANGE THIS
define('DB_NAME', 'contactmgt');
```

3. Replace `your_username` and `your_password` with your actual credentials

### Option B: Use the configuration wizard (recommended)

Run the `setup_wizard.php` file I created - it will guide you through the setup!

---

## Step 3: Create the Database

### Method 1: Using phpMyAdmin (EASIEST)

1. Go to: `https://apps.ashesi.edu.gh/phpmyadmin` (or your phpMyAdmin URL)
2. Log in with your credentials
3. Click **"New"** in the left sidebar
4. Database name: `contactmgt`
5. Click **"Create"**
6. Click on the `contactmgt` database you just created
7. Click **"Import"** tab
8. Click **"Choose File"** and select `database/schema.sql`
9. Click **"Go"** at the bottom
10. ✅ Done! You should see the `contacts` table with 5 sample records

### Method 2: Using MySQL Console

```bash
# Connect to MySQL
mysql -u your_username -p

# Create database
CREATE DATABASE contactmgt;
USE contactmgt;

# Copy and paste the entire schema.sql content
# Or run: source /path/to/schema.sql
```

---

## Step 4: Upload Files to Server

### Files to Upload:

```
Upload to: /var/www/html/contactmgt/
├── actions/                          (all 5 PHP files)
│   ├── get_all_contact_mob.php
│   ├── add_contact_mob.php
│   ├── get_a_contact_mob.php
│   ├── update_contact.php
│   └── delete_contact.php
│
└── database/                         (connection file)
    └── db_connection.php             (with YOUR credentials)
```

### Using FTP/SFTP:
1. Connect to your Ashesi server with FTP client (FileZilla, WinSCP, etc.)
2. Navigate to `/var/www/html/` or your public_html folder
3. Create `contactmgt` folder if it doesn't exist
4. Upload the folders

### Using Command Line:
```bash
scp -r actions/ your_username@apps.ashesi.edu.gh:/var/www/html/contactmgt/
scp -r database/ your_username@apps.ashesi.edu.gh:/var/www/html/contactmgt/
```

---

## Step 5: Test Your API

### Quick Browser Test:

Open in your browser:
```
https://apps.ashesi.edu.gh/contactmgt/actions/get_all_contact_mob.php
```

**✅ Success if you see:**
```json
[
  {"pid":"1","pname":"John Doe","pphone":"+233501234567"},
  {"pid":"2","pname":"Jane Smith","pphone":"+233241234567"}
]
```

**❌ Error if you see:**
- Blank page → Check PHP errors in server logs
- Database connection error → Wrong credentials in db_connection.php
- 404 Not Found → Wrong file path on server

---

## Step 6: Test All Endpoints

Use the HTML tester:
```
Open: tests/test_api.html in your browser
```

Or use CURL:
```bash
# Test Get All
curl https://apps.ashesi.edu.gh/contactmgt/actions/get_all_contact_mob.php

# Test Add Contact
curl -X POST https://apps.ashesi.edu.gh/contactmgt/actions/add_contact_mob.php \
  -H "Content-Type: application/json" \
  -d '{"ufullname":"Test User","uphonename":"+233501234567"}'
```

---

## 🔧 Troubleshooting

### "Database connection failed"
✅ Check credentials in `db_connection.php`
✅ Make sure database `contactmgt` exists
✅ Verify user has permissions to access database

### "404 Not Found"
✅ Verify file paths on server
✅ Check that files were uploaded correctly
✅ Make sure you're using the correct URL

### "500 Internal Server Error"
✅ Check PHP error logs
✅ Look for syntax errors in PHP files
✅ Make sure PHP version is 7.0 or higher

### "CORS Error" in browser
✅ Already handled in the PHP files
✅ If persists, check server CORS configuration

---

## ✅ Setup Checklist

- [ ] Database credentials obtained
- [ ] `db_connection.php` configured with credentials
- [ ] Database `contactmgt` created
- [ ] `schema.sql` imported (5 sample contacts added)
- [ ] Files uploaded to server
- [ ] Browser test successful (can see JSON contacts)
- [ ] All 5 endpoints tested
- [ ] Screenshots taken for submission

---

## 🎉 You're Done!

Your API is now live at:
```
https://apps.ashesi.edu.gh/contactmgt/actions/
```

**Next:** Test all 5 endpoints and take screenshots for your lab submission!

---

## 📞 Need Help?

If you get stuck:
1. Check the error message carefully
2. Look at PHP error logs on server
3. Verify database credentials
4. Make sure all files are uploaded
5. Test with the HTML tester or Postman

**Common URLs to check:**
- phpMyAdmin: `https://apps.ashesi.edu.gh/phpmyadmin`
- Your API: `https://apps.ashesi.edu.gh/contactmgt/actions/`
- Error logs: Usually in `/var/log/apache2/error.log` or via cPanel

Good luck! 🚀
