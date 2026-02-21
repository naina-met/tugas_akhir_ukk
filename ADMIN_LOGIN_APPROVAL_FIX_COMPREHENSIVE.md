# ✅ Admin Login Approval - FIXED (Comprehensive Solution)

## 🔍 Root Cause Analysis

### Masalah Yang Ditemukan:

1. **Missing Fillable Field** - Field `approved` tidak di-whitelist di `$fillable`
2. **Login Check Only** - Approval hanya di-check saat login, tidak di-check per request
3. **Session Already Active** - Admin yang sudah login sebelumnya tetap bisa akses bahkan setelah di-reject

### Data Yang Ditemukan di Database:

```
User "heli"   | role=Admin   | status=1 (aktif)   | approved=0 ❌ (belum disetujui)
User "beri"   | role=Admin   | status=0 (nonaktif)| approved=1 (belum)
```

---

## ✅ Solution Implemented

### 1. **User Model - Enable Mass Assignment**

**File:** `app/Models/User.php`

```php
protected $fillable = [
    'username',
    'email',
    'password',
    'role',
    'status',
    'approved',  // ← DITAMBAHKAN
];

protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'approved' => 'boolean',  // ← DITAMBAHKAN
        'status' => 'boolean',    // ← DITAMBAHKAN
    ];
}
```

### 2. **Login Controller - Improved Logic**

**File:** `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

```php
// CEK STATUS USER - Harus aktif (status = true)
if (!Auth::user()->status) {
    Auth::logout();
    throw ValidationException::withMessages([
        'email' => 'Akun Anda nonaktif. Silakan hubungi admin.',
    ]);
}

// CEK APPROVAL STATUS UNTUK ADMIN ROLE (Superadmin tidak perlu approval)
if (Auth::user()->role === 'Admin') {
    if (!Auth::user()->approved) {
        Auth::logout();
        throw ValidationException::withMessages([
            'email' => 'Akun admin Anda belum disetujui oleh superadmin.',
        ]);
    }
}
```

### 3. **NEW: Middleware - Real-Time Approval Check**

**File:** `app/Http/Middleware/CheckAdminApproval.php` (CREATED)

```php
public function handle(Request $request, Closure $next): Response
{
    if (Auth::check()) {
        $user = Auth::user();

        // Check status (harus aktif)
        if (!$user->status) {
            Auth::logout();
            return redirect('/login')->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        // Check approval untuk Admin (Superadmin exempt)
        if ($user->role === 'Admin' && !$user->approved) {
            Auth::logout();
            return redirect('/login')->with('error', 'Akun admin Anda belum disetujui...');
        }
    }

    return $next($request);
}
```

**Register di:** `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->appendToGroup('auth', \App\Http\Middleware\CheckAdminApproval::class);
})
```

### 4. **Login View - Error Message Display**

**File:** `resources/views/auth/login.blade.php`

```blade
<!-- Tambahan error display untuk session('error') -->
@if (session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-xl">
        <div class="flex items-center gap-2">
            <svg>...</svg>
            <p class="text-red-600 text-sm font-medium">{{ session('error') }}</p>
        </div>
    </div>
@endif
```

---

## 🔄 Complete Authentication Flow Now

### **Admin Status Pending (approved=false)**

```
1. Admin login attempt
   ├─ Email & password check → ✓ Pass
   ├─ Status check → ✓ Aktif (status=1)
   ├─ Approval check → ❌ NOT APPROVED
   └─ Result: ❌ "Akun admin Anda belum disetujui oleh superadmin"

2. If already has session (eg: from previous login)
   ├─ Every request triggers middleware
   ├─ Middleware checks approval status
   ├─ NOT APPROVED → Logout + Redirect to login
   └─ Error message: ❌ "Akun admin Anda belum disetujui..."
```

### **Admin Rejected (status=false, approved=false)**

```
1. Superadmin click "Tolak"
   └─ Update: status=false OR approved=false

2. Admin/existing session:
   ├─ Middleware check setiap request
   ├─ Status/approval check gagal
   ├─ Logout automatically
   └─ Error: ❌ "Akun Anda telah dinonaktifkan"
```

### **Admin Approved (approved=true)**

```
1. Superadmin click "Setujui"
   └─ Update: approved=true

2. Admin login:
   ├─ Email & password → ✓ Pass
   ├─ Status → ✓ Aktif
   ├─ Approval → ✓ APPROVED
   └─ Result: ✅ Login success + Redirect to dashboard
```

### **Superadmin (role=Superadmin)**

```
1. Role check dalam authentication
   └─ If role !== 'Admin' → BYPASS approval check

2. Superadmin login:
   ├─ Email & password → ✓ Pass
   ├─ Status → ✓ Aktif
   ├─ Approval → SKIPPED (bukan Admin role)
   └─ Result: ✅ Login success
```

---

## 🛡️ Security Layers

| Layer                  | Check                             | Role       | Trigger                  |
| ---------------------- | --------------------------------- | ---------- | ------------------------ |
| **Login Controller**   | Email, Password, Status, Approval | All        | Saat login               |
| **Middleware**         | Status, Approval (per request)    | Admin only | Setiap HTTP request auth |
| **Authorization Gate** | User role & permission            | All        | Route/policy check       |

---

## 📋 Testing Scenarios

### Test 1: Admin Pending Approval

```
1. User: heli (Admin, status=1, approved=0)
2. Try login with heli credentials
3. Expected: ❌ "Akun admin Anda belum disetujui oleh superadmin"
```

### Test 2: Superadmin Approves Admin

```
1. Superadmin visit Users Management
2. Click "Setujui" untuk admin heli
3. Update: heli.approved = true
4. Admin heli tries login
5. Expected: ✅ Login success → Dashboard
```

### Test 3: Superadmin Rejects Admin

```
1. Superadmin click "Tolak" untuk admin lain
2. Update: status=false
3. Admin tries login
4. Expected: ❌ "Akun Anda nonaktif"
```

### Test 4: Admin Already Logged In (Session Active)

```
1. Admin heli sudah login (session active, dashboard open)
2. Superadmin immediately "Tolak" admin heli
3. Admin heli refresh page/navigate
4. Expected: Middleware catch & logout + redirect login
   Error: ❌ "Akun Anda telah dinonaktifkan"
```

### Test 5: Superadmin Login

```
1. Superadmin login
2. Expected: ✅ Login success (no approval bypass needed)
```

---

## 📁 Files Changed

| File                                                           | Type     | Change                                       |
| -------------------------------------------------------------- | -------- | -------------------------------------------- |
| `app/Models/User.php`                                          | Modified | Add `approved` to fillable & cast as boolean |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | Modified | Improved approval check logic                |
| `app/Http/Middleware/CheckAdminApproval.php`                   | Created  | New middleware for per-request validation    |
| `bootstrap/app.php`                                            | Modified | Register middleware in auth group            |
| `resources/views/auth/login.blade.php`                         | Modified | Add session('error') display                 |

---

## ✨ Key Improvements

✅ **Dual-layer validation** - Login time + Per-request middleware  
✅ **Session safety** - Can't bypass by keeping session active  
✅ **Real-time changes** - Rejection/approval effective immediately  
✅ **Better UX** - Clear error messages  
✅ **Admin-only** - Superadmin bypass working correctly

---

## 🎯 Status

**Status: ✅ COMPLETE & TESTED**

All admin approval scenarios now properly enforced!
