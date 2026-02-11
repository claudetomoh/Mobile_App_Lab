# API Testing Guide
## Contact Management System

---

## 🧪 Testing Checklist

### ✅ Pre-Testing Setup
- [ ] Database created and populated with sample data
- [ ] Database credentials configured in `db_connection.php`
- [ ] All 5 PHP files uploaded to server
- [ ] Server is accessible

---

## 📋 Test Cases

### Test 1: Get All Contacts

**Endpoint:** `GET /get_all_contact_mob.php`

**Test Scenarios:**

1. **Success Case - Database has contacts**
   - **Action:** Make GET request
   - **Expected:** 200 OK, JSON array of contacts
   - **Verify:** Each contact has `pid`, `pname`, `pphone`

2. **Success Case - Empty database**
   - **Action:** Make GET request with no contacts in DB
   - **Expected:** 200 OK, Empty array `[]`

3. **Error Case - Database connection fail**
   - **Action:** Wrong DB credentials
   - **Expected:** 500 Error, Error message

**Test Command:**
```bash
curl -X GET https://apps.ashesi.edu.gh/contactmgt/actions/get_all_contact_mob.php
```

**Expected Response:**
```json
[
  {"pid":"1","pname":"John Doe","pphone":"+233501234567"},
  {"pid":"2","pname":"Jane Smith","pphone":"+233241234567"}
]
```

---

### Test 2: Add New Contact

**Endpoint:** `POST /add_contact_mob.php`

**Test Scenarios:**

1. **Success Case - Valid data**
   - **Input:** `{"ufullname":"Test User","uphonename":"+233501234567"}`
   - **Expected:** 200 OK, `"success"`

2. **Error Case - Missing name**
   - **Input:** `{"uphonename":"+233501234567"}`
   - **Expected:** 400 Bad Request, `"failed - Missing required fields"`

3. **Error Case - Missing phone**
   - **Input:** `{"ufullname":"Test User"}`
   - **Expected:** 400 Bad Request, `"failed - Missing required fields"`

4. **Error Case - Empty name**
   - **Input:** `{"ufullname":"","uphonename":"+233501234567"}`
   - **Expected:** 400 Bad Request, `"failed - Name cannot be empty"`

5. **Error Case - Short phone number**
   - **Input:** `{"ufullname":"Test","uphonename":"123"}`
   - **Expected:** 400 Bad Request, `"failed - Phone number must be at least 10 digits"`

**Test Command:**
```bash
curl -X POST https://apps.ashesi.edu.gh/contactmgt/actions/add_contact_mob.php \
  -H "Content-Type: application/json" \
  -d '{"ufullname":"Test User","uphonename":"+233501234567"}'
```

**Expected Response:**
```json
"success"
```

---

### Test 3: Get One Contact

**Endpoint:** `GET /get_a_contact_mob.php?contid={id}`

**Test Scenarios:**

1. **Success Case - Valid contact ID**
   - **Input:** `contid=1`
   - **Expected:** 200 OK, Contact object with `pid`, `pname`, `pphone`

2. **Error Case - Contact not found**
   - **Input:** `contid=99999`
   - **Expected:** 404 Not Found, `{"status":"error","message":"Contact not found"}`

3. **Error Case - Missing contid**
   - **Input:** No parameter
   - **Expected:** 400 Bad Request, `{"status":"error","message":"Missing required parameter: contid"}`

4. **Error Case - Invalid contid**
   - **Input:** `contid=abc`
   - **Expected:** 400 Bad Request, Invalid contact ID

**Test Command:**
```bash
curl -X GET "https://apps.ashesi.edu.gh/contactmgt/actions/get_a_contact_mob.php?contid=1"
```

**Expected Response:**
```json
{
  "pid":"1",
  "pname":"John Doe",
  "pphone":"+233501234567"
}
```

---

### Test 4: Update Contact

**Endpoint:** `POST /update_contact.php`

**Test Scenarios:**

1. **Success Case - Valid update**
   - **Input:** `{"cid":1,"cname":"Updated Name","cnum":"+233509876543"}`
   - **Expected:** 200 OK, `"success"`

2. **Error Case - Contact not found**
   - **Input:** `{"cid":99999,"cname":"Name","cnum":"+233501234567"}`
   - **Expected:** 404 Not Found, `"failed - Contact not found"`

3. **Error Case - Missing fields**
   - **Input:** `{"cid":1}`
   - **Expected:** 400 Bad Request, `"failed - Missing required fields"`

4. **Error Case - Empty name**
   - **Input:** `{"cid":1,"cname":"","cnum":"+233501234567"}`
   - **Expected:** 400 Bad Request, `"failed - Name cannot be empty"`

5. **Error Case - Invalid ID**
   - **Input:** `{"cid":0,"cname":"Name","cnum":"+233501234567"}`
   - **Expected:** 400 Bad Request, `"failed - Invalid contact ID"`

**Test Command:**
```bash
curl -X POST https://apps.ashesi.edu.gh/contactmgt/actions/update_contact.php \
  -H "Content-Type: application/json" \
  -d '{"cid":1,"cname":"Updated Name","cnum":"+233509876543"}'
```

**Expected Response:**
```json
"success"
```

---

### Test 5: Delete Contact

**Endpoint:** `POST /delete_contact.php`

**Test Scenarios:**

1. **Success Case - Delete existing contact**
   - **Input:** `{"cid":1}`
   - **Expected:** 200 OK, `true`

2. **Error Case - Contact not found**
   - **Input:** `{"cid":99999}`
   - **Expected:** 404 Not Found, `false`

3. **Error Case - Missing cid**
   - **Input:** `{}`
   - **Expected:** 400 Bad Request, `false`

4. **Error Case - Invalid cid**
   - **Input:** `{"cid":0}`
   - **Expected:** 400 Bad Request, `false`

**Test Command:**
```bash
curl -X POST https://apps.ashesi.edu.gh/contactmgt/actions/delete_contact.php \
  -H "Content-Type: application/json" \
  -d '{"cid":1}'
```

**Expected Response:**
```json
true
```

---

## 🔄 Integration Testing Workflow

### Complete CRUD Test Sequence:

```bash
# 1. Get all contacts (should show sample data)
curl -X GET https://apps.ashesi.edu.gh/contactmgt/actions/get_all_contact_mob.php

# 2. Add a new contact
curl -X POST https://apps.ashesi.edu.gh/contactmgt/actions/add_contact_mob.php \
  -H "Content-Type: application/json" \
  -d '{"ufullname":"Test User","uphonename":"+233501234567"}'

# 3. Get all contacts again (should show new contact)
curl -X GET https://apps.ashesi.edu.gh/contactmgt/actions/get_all_contact_mob.php

# 4. Get the new contact (assume ID = 6)
curl -X GET "https://apps.ashesi.edu.gh/contactmgt/actions/get_a_contact_mob.php?contid=6"

# 5. Update the contact
curl -X POST https://apps.ashesi.edu.gh/contactmgt/actions/update_contact.php \
  -H "Content-Type: application/json" \
  -d '{"cid":6,"cname":"Updated Test User","cnum":"+233509999999"}'

# 6. Get the contact again (verify update)
curl -X GET "https://apps.ashesi.edu.gh/contactmgt/actions/get_a_contact_mob.php?contid=6"

# 7. Delete the contact
curl -X POST https://apps.ashesi.edu.gh/contactmgt/actions/delete_contact.php \
  -H "Content-Type: application/json" \
  -d '{"cid":6}'

# 8. Try to get deleted contact (should return 404)
curl -X GET "https://apps.ashesi.edu.gh/contactmgt/actions/get_a_contact_mob.php?contid=6"
```

---

## 📊 Test Results Template

### Endpoint Test Results

| Endpoint | Method | Test Case | Status | Notes |
|----------|--------|-----------|--------|-------|
| get_all_contact_mob.php | GET | Success with data | ✅ | Returns array |
| get_all_contact_mob.php | GET | Empty database | ✅ | Returns [] |
| add_contact_mob.php | POST | Valid data | ✅ | Returns "success" |
| add_contact_mob.php | POST | Missing fields | ✅ | Returns error |
| get_a_contact_mob.php | GET | Valid ID | ✅ | Returns contact |
| get_a_contact_mob.php | GET | Invalid ID | ✅ | Returns 404 |
| update_contact.php | POST | Valid update | ✅ | Returns "success" |
| update_contact.php | POST | Contact not found | ✅ | Returns error |
| delete_contact.php | POST | Valid delete | ✅ | Returns true |
| delete_contact.php | POST | Contact not found | ✅ | Returns false |

---

## 🎯 Pass Criteria

An endpoint passes testing if:

✅ Returns correct HTTP status code  
✅ Returns expected response format  
✅ Handles errors gracefully  
✅ Validates inputs properly  
✅ No SQL injection vulnerabilities  
✅ No PHP errors/warnings  
✅ Consistent response structure  

---

## 🐛 Common Issues & Solutions

### Issue: CORS Error in Browser
**Solution:** Headers are already set in PHP files. Check browser console for specific error.

### Issue: Empty Response
**Solution:** Check PHP error logs, verify database connection.

### Issue: 500 Internal Server Error
**Solution:** Check PHP syntax errors, database credentials, error logs.

### Issue: JSON Parse Error
**Solution:** Ensure Content-Type header is set, check JSON format.

---

## 📸 Screenshot Guide for Submission

Take screenshots of:

1. **Postman/CURL** - All 5 successful endpoint tests
2. **Database** - phpMyAdmin showing contacts table with data
3. **Test Results** - HTML test page showing all tests passing
4. **Error Handling** - Examples of validation errors
5. **Code** - Key sections (at least one endpoint)

---

## ✅ Final Testing Checklist

Before submission:

- [ ] All 5 endpoints tested and working
- [ ] Both success and error cases tested
- [ ] CRUD workflow tested (create → read → update → delete)
- [ ] Screenshots taken
- [ ] Database populated with sample data
- [ ] Code reviewed for errors
- [ ] Documentation reviewed
- [ ] Response formats match specification

---

**Good luck with testing! 🚀**
