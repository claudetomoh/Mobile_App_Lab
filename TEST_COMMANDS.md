# API Testing Commands for Submission

**Student:** Tomoh Claude Ikfingeh  
**API Base URL:** http://169.239.251.102:280/~tomoh.ikfingeh/Mobile_App_Lab/actions  
**GitHub:** https://github.com/claudetomoh/Mobile_App_Lab

---

## Test 1: Read All Contacts (GET)

**Command:**
```bash
curl "http://169.239.251.102:280/~tomoh.ikfingeh/Mobile_App_Lab/actions/get_all_contact_mob.php"
```

**Expected Response Format:**
```json
{
  "success": true,
  "data": [
    {"id": "1", "name": "John Doe", "phone": "+233501234567"},
    {"id": "2", "name": "Jane Smith", "phone": "+233241234567"}
  ]
}
```

---

## Test 2: Read One Contact (GET)

**Command:**
```bash
curl "http://169.239.251.102:280/~tomoh.ikfingeh/Mobile_App_Lab/actions/get_a_contact_mob.php?id=1"
```

**Expected Response Format:**
```json
{
  "success": true,
  "data": {
    "id": "1",
    "name": "John Doe",
    "phone": "+233501234567"
  }
}
```

---

## Test 3: Create New Contact (POST) + Verification

**Step 1 - Create:**
```bash
curl -X POST "http://169.239.251.102:280/~tomoh.ikfingeh/Mobile_App_Lab/actions/add_contact_mob.php" \
  -d "name=Alice Cooper" \
  -d "phone=+233205555555"
```

**Expected Response:**
```json
{
  "success": true,
  "data": {"id": 8}
}
```

**Step 2 - Verify (Read All):**
```bash
curl "http://169.239.251.102:280/~tomoh.ikfingeh/Mobile_App_Lab/actions/get_all_contact_mob.php"
```

**Expected:** Should show the new contact with id=8

---

## Test 4: Update Contact (POST) + Verification

**Step 1 - Update:**
```bash
curl -X POST "http://169.239.251.102:280/~tomoh.ikfingeh/Mobile_App_Lab/actions/update_contact.php" \
  -d "id=3" \
  -d "name=Michael Johnson Updated" \
  -d "phone=+233207777777"
```

**Expected Response:**
```json
{
  "success": true
}
```

**Step 2 - Verify (Read One):**
```bash
curl "http://169.239.251.102:280/~tomoh.ikfingeh/Mobile_App_Lab/actions/get_a_contact_mob.php?id=3"
```

**Expected:** Should show updated name and phone

---

## Test 5: Delete Contact (POST) + Verification

**Step 1 - Delete:**
```bash
curl -X POST "http://169.239.251.102:280/~tomoh.ikfingeh/Mobile_App_Lab/actions/delete_contact.php" \
  -d "id=7"
```

**Expected Response:**
```json
{
  "success": true
}
```

**Step 2 - Verify (Read All):**
```bash
curl "http://169.239.251.102:280/~tomoh.ikfingeh/Mobile_App_Lab/actions/get_all_contact_mob.php"
```

**Expected:** Contact with id=7 should not appear in the list

---

## Summary

All API endpoints follow the required format:
- **Success responses:** `{"success": true, "data": {...}}`
- **Error responses:** `{"success": false, "error": "message"}`
- **Parameters:** Standardized to `id`, `name`, `phone`

---

## Submission Checklist

- [x] GitHub Repository: https://github.com/claudetomoh/Mobile_App_Lab
- [x] Deployed API: http://169.239.251.102:280/~tomoh.ikfingeh/Mobile_App_Lab/actions
- [ ] PDF Report with screenshots of all 5 tests
