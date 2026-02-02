# Security Patch Summary - Version 1.0.1

## Date: 2026-02-02

This document summarizes all security vulnerabilities and bugs that were fixed in this patch release.

---

## CRITICAL Security Fixes

### 1. Password Storage Vulnerability FIXED
**Issue**: Passwords were stored in plain text in the database  
**Impact**: Complete compromise of user credentials  
**Files Modified**:
- `src/Repository/UserRepository.php` - Added `password_hash()` in insert method
- `src/Controller/SecurityController.php` - Changed to use `password_verify()` for authentication

**Solution**: 
- Implemented PHP's `password_hash()` with `PASSWORD_DEFAULT` algorithm
- Updated login to use `password_verify()` for secure password comparison
- All new passwords will be automatically hashed
- **Note**: Existing passwords in database need to be reset or migrated

---

### 2. SQL Injection Vulnerabilities   FIXED
**Issue**: Multiple SQL queries used string concatenation/interpolation instead of prepared statements  
**Impact**: Complete database compromise, data theft, data manipulation  
**Files Modified**:
- `src/Repository/UserRepository.php`
  - `find()` method - Fixed SQL injection with ID parameter
  - `findByEmail()` method - Fixed SQL injection with email parameter
  
- `src/Repository/HabitRepository.php`
  - `find()` method - Fixed SQL injection with ID parameter
  - `findByUser()` method - Fixed SQL injection with user_id parameter
  - `insert()` method - Fixed SQL injection with name and description parameters
  
- `src/Repository/HabitLogRepository.php`
  - `findByHabit()` method - Fixed SQL injection with habit_id parameter

**Solution**: Replaced all vulnerable queries with PDO prepared statements using parameter binding

**Example of fix**:
```php
// BEFORE (VULNERABLE):
$sql = "SELECT * FROM mns_user WHERE email = '$email'";
$query = $this->getConnection()->query($sql);

// AFTER (SECURE):
$sql = "SELECT * FROM mns_user WHERE email = :email";
$stmt = $this->getConnection()->prepare($sql);
$stmt->execute(['email' => $email]);
```

---

## HIGH Security Fixes

### 3. Missing Access Control   FIXED
**Issue**: Admin user management routes were accessible to non-admin users  
**Impact**: Unauthorized users could access and potentially manipulate user accounts  
**Files Modified**:
- `config/routes.json` - Added `AdminGuard` to `/admin/user` and `/admin/user/new` routes

**Solution**: Added proper guard protection to ensure only admin users can access user management

---

##  MEDIUM Security Fixes

### 4. Cross-Site Scripting (XSS) Vulnerabilities   FIXED
**Issue**: Error messages were displayed without HTML escaping  
**Impact**: Potential XSS attacks through error messages  
**Files Modified**:
- `templates/admin/user/new.html.php`
- `templates/register/index.html.php`
- `templates/member/habits/new.html.php`
- `templates/security/login.html.php`

**Solution**: Added `htmlspecialchars()` to all error message outputs

**Example of fix**:
```php
// BEFORE (VULNERABLE):
<?= $error ?>

// AFTER (SECURE):
<?= htmlspecialchars($error) ?>
```

---

## 🐛 Bug Fixes

### 5. Registration Form Not Working   FIXED
**Issue**: Form checked `$_GET['user']` instead of `$_POST['user']`  
**Impact**: Registration form never processed submissions  
**Files Modified**:
- `src/Controller/RegisterController.php`

**Solution**: Changed condition from `$_GET['user']` to `$_POST['user']`

---

### 6. 404 Error After Creating Habit   FIXED
**Issue**: Redirect URL was `/habit` instead of `/habits`  
**Impact**: Users got 404 error after successfully creating a habit  
**Files Modified**:
- `src/Controller/Member/HabitsController.php`

**Solution**: Corrected redirect URL to match existing route

---

### 7. Wrong Registration Redirect   FIXED
**Issue**: Registration redirected to non-existent `/user/ticket` route  
**Impact**: 404 error after successful registration  
**Files Modified**:
- `src/Controller/RegisterController.php`

**Solution**: Changed redirect to `/dashboard`

---

### 8. API Fatal Error   FIXED
**Issue**: Class name mismatch - `HabitController` vs `HabitsController`  
**Impact**: Fatal error when accessing `/api/habits`  
**Files Modified**:
- `src/Controller/Api/HabitsController.php`

**Solution**: Renamed class to match route configuration

---

### 9. Login Flow Bug   FIXED
**Issue**: Missing `exit` statement after redirect for non-admin users  
**Impact**: Code continued executing after redirect  
**Files Modified**:
- `src/Controller/SecurityController.php`

**Solution**: Added `exit` statement after dashboard redirect

---

### 10. open_basedir Restriction Error   FIXED
**Issue**: PHP `open_basedir` restriction prevented access to `/vendor/autoload.php`  
**Impact**: Fatal error on production server - application completely broken  
**Files Modified**:
- `public/.user.ini` - Created with proper `open_basedir` configuration
- `public/index.php` - Updated to use `__DIR__` for more robust path resolution

**Solution**: 
- Created `.user.ini` file to extend `open_basedir` to include parent directory
- Changed relative paths to use `__DIR__` constant for absolute path resolution

## Deployment Instructions

Follow the standard deployment procedure from `PROCEDURE.md`:

1. Commit all changes:
   ```bash
   git add .
   git commit -m "security: Fix critical vulnerabilities and bugs v1.0.1"
   ```

2. Update changelog:
   ```bash
   git-cliff --bump -o ./CHANGELOG.md
   ```

3. Tag the release:
   ```bash
   git add .
   git commit -m "version 1.0.1"
   git tag 1.0.1
   ```

4. Push to origin and VPS:
   ```bash
   git push origin main
   git push vps 1.0.1
   ```

5. Deploy on server:
   ```bash
   bash /deploy.sh 1.0.1
   ```

6. **CRITICAL POST-DEPLOYMENT STEP**: 
   - All existing user passwords need to be reset since they were stored in plain text
   - Consider sending password reset emails to all users
   - Or run a migration script to hash existing passwords (if you have them in plain text)

---

## Summary Statistics

- **Total Files Modified**: 13
- **Critical Vulnerabilities Fixed**: 2 (Password storage, SQL Injection)
- **High Vulnerabilities Fixed**: 1 (Access control)
- **Medium Vulnerabilities Fixed**: 1 (XSS)
- **Bugs Fixed**: 6
- **Total Security Issues**: 4
- **Total Issues Fixed**: 10

---
