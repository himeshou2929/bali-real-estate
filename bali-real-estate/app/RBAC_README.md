# Role-Based Access Control (RBAC) System

## User Roles
- **Admin**: Full system access
- **Agent**: Property management and assigned inquiry management
- **Viewer**: Read-only access to inquiries
- **User**: General user (standard customers)

## Usage Examples

### Role Middleware
```php
// Single role
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// Multiple roles
Route::middleware(['auth', 'role:admin,agent'])->group(function () {
    Route::resource('properties', PropertyController::class);
});
```

### User Role Helper Methods
```php
$user = auth()->user();

if ($user->isAdmin()) {
    // Admin-only logic
}

if ($user->isAgent()) {
    // Agent logic
}

if ($user->isViewer()) {
    // Viewer logic
}
```

### Policy Usage in Controllers
```php
// Check permissions
$this->authorize('update', $inquiry);
$this->authorize('create', Property::class);

// In Blade templates
@can('update', $inquiry)
    <button>Edit</button>
@endcan

@cannot('delete', $property)
    <span>Cannot delete</span>
@endcannot
```

### Policy Methods

#### InquiryPolicy
- `view()`: Admin, assigned agent, or viewer
- `update()`: Admin or assigned agent
- `assign()`: Admin only
- `reply()`: Admin or assigned agent

#### PropertyPolicy
- `view()`: All users
- `create()`: Admin or agent
- `update()`: Admin or property owner agent
- `delete()`: Admin or property owner agent

## Implementation Files
- `app/Enums/UserRole.php` - Role enumeration
- `app/Http/Middleware/RoleMiddleware.php` - Role checking middleware
- `app/Policies/InquiryPolicy.php` - Inquiry permissions
- `app/Policies/PropertyPolicy.php` - Property permissions
- `app/Providers/AuthServiceProvider.php` - Policy registration