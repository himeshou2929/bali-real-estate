# User Dashboard System

## Overview
A comprehensive user dashboard and profile management system for authenticated users of the Bali real estate application.

## Features

### Dashboard (`/dashboard`)
- **User Welcome**: Personalized greeting with user name
- **Favorites Section**: 
  - Display up to 12 most recent favorite properties
  - Property cards with image, title, price, and status
  - Link to view full property details
  - "See all" link to favorites page
- **Inquiries Section**:
  - Display up to 10 most recent user inquiries
  - Shows subject, status, creation date, and related property
  - Status badges with color coding (green=replied, yellow=new, gray=other)
  - Links to view related properties
- **Empty States**: User-friendly messages when no data is available

### Profile Management (`/profile`)
- **User Information**: Name, email (read-only), phone
- **Preferences**: Language (English/Japanese/Indonesian), Currency (USD/JPY/IDR)
- **Form Validation**: Required fields and format validation
- **Success Feedback**: Visual confirmation when profile is updated

## Technical Implementation

### Backend Components

#### Controllers
- `App\Http\Controllers\User\DashboardController`:
  - `index()`: Dashboard data retrieval
  - `profile()`: Profile form display
  - `updateProfile()`: Profile data update

#### Models
- Extended `User` model with new relationships:
  - `favoriteProperties()`: Many-to-many with Properties via favorites table
  - `inquiries()`: One-to-many with Inquiries
- Added fillable fields: `phone`, `language`, `currency`

#### Routes
- `GET /dashboard` → Dashboard page (replaces Breeze default)
- `GET /profile` → Profile form
- `POST /profile` → Profile update
- All routes protected with `auth` middleware

### Frontend Components

#### Vue Pages
- `resources/js/Pages/User/Dashboard.vue`:
  - Responsive grid layout for favorites
  - Tabular layout for inquiries
  - Internationalized currency formatting
  - Status badge system
- `resources/js/Pages/User/Profile.vue`:
  - Form with validation
  - Dropdown selectors for preferences
  - Inertia.js form handling

#### UI Components (Reused)
- `Badge.vue`: Status indicators with color variants
- `EmptyState.vue`: User-friendly empty data displays

## Data Flow

### Dashboard Loading
1. User accesses `/dashboard`
2. Controller fetches:
   - Latest 12 favorite properties with relations
   - Latest 10 user inquiries with property info
3. Data transformed and passed to Vue component
4. Component renders responsive layout

### Profile Update
1. User submits profile form
2. Laravel validates input data
3. User model updated with new information
4. Success feedback displayed
5. Form stays on same page (preserveScroll)

## Database Relations

### User → Favorites
```sql
SELECT properties.* FROM properties
JOIN favorites ON favorites.property_id = properties.id
WHERE favorites.user_id = ? 
ORDER BY favorites.created_at DESC
```

### User → Inquiries
```sql
SELECT inquiries.*, properties.title_en, properties.price 
FROM inquiries
LEFT JOIN properties ON inquiries.property_id = properties.id
WHERE inquiries.user_id = ?
ORDER BY inquiries.created_at DESC
```

## Styling & Responsiveness
- **Mobile-first**: Responsive grid system
- **Tailwind CSS**: Utility-first styling
- **Grid Layouts**: 1 column mobile → 2 columns tablet → 3 columns desktop
- **Interactive Elements**: Hover effects, status badges, consistent spacing

## Integration Points
- **Authentication**: Laravel Breeze integration
- **Property System**: Links to property detail pages
- **Favorites System**: Integration with existing favorites functionality
- **Inquiry System**: Display of user's inquiry history
- **Navigation**: Profile edit links, breadcrumbs

## Usage
1. Users must be authenticated to access dashboard
2. Dashboard automatically displays recent activity
3. Profile can be updated anytime with new preferences
4. System supports multiple languages and currencies
5. All data updates are real-time via Inertia.js