# PiggyTrunk - Database Connection & Dashboard Setup

## Summary of Changes

This document outlines all the patches made to fix the database connection and set up the dashboard with proper data fetching.

### 1. Database Schema Setup

#### Created Missing Users Table Migration
**File:** `database/migrations/2024_01_01_000001_create_users_table.php`

The system was missing the foundational `users` table that other tables depend on. Created a standard Laravel users table with:
- User authentication fields (name, email, password)
- Email verification
- Authentication tokens
- Sessions table for session management

#### Key Impact
- Fixed foreign key constraint errors when creating the raisers table
- Established the base user authentication system
- Required for both admin and user management features

### 2. Pig Type Classification System

#### Created PigTypeSeeder
**File:** `database/seeders/PigTypeSeeder.php`

Seeded the system with four pig types:
- **Fattening:** Hogs raised for meat production
- **Sow:** Female hogs for breeding purposes
- **Piglet:** Young pigs in early stage
- **Boar:** Male hogs for breeding purposes

This data is essential for:
- Classifying and managing different hog types
- Lifecycle stage management based on pig type
- Investment allocation tracking by pig type

### 3. Sample Data Population

#### Created SampleDataSeeder
**File:** `database/seeders/SampleDataSeeder.php`

Populated the database with comprehensive test data:

**Batch Lifecycle Stages (5 stages)**
1. Booster (14 days) - Initial boost phase
2. Pre-Starter (45 days) - Pre-feeding phase
3. Starter (60 days) - Starter feed phase
4. Grower (120 days) - Growth phase
5. Finisher (60 days) - Final fattening phase

**Test Investors (3 active investors)**
- Juan dela Cruz (Manila)
- Maria Garcia (Quezon City)
- Carlos Lopez (Makati)

**Active Batches (3 batches)**
1. BATCH-FAT-2024-001: John Doe's fattening operation (100 hogs)
2. BATCH-SOW-2024-001: Maria Santos' sow operation (50 sows)
3. BATCH-FAT-2024-002: John Doe's second fattening batch (150 hogs)

**Investment Tracking**
- Total sample investment: ₱200,000
- Expected collective profit: ₱85,000
- Split among 3 investors

### 4. Model Relationship Fixes

#### Fixed Raiser Model (`app/Models/Raiser.php`)
**Changes:**
- Updated fillable attributes to include `user_id` and `pig_type_id`
- Corrected field name from `pig_type` to `pig_type_id` to match database schema

**Before:**
```php
protected $fillable = [
    'name', 'code', 'phone', 'email', 'address',
    'pig_type', // ❌ WRONG - this is not a field
    'status', ...
];
```

**After:**
```php
protected $fillable = [
    'user_id', 'name', 'code', 'phone', 'email', 'address',
    'pig_type_id', // ✅ CORRECT - foreign key to pig_types table
    'capacity', 'status', ...
];
```

#### Fixed DashboardController (`app/Http/Controllers/DashboardController.php`)
**Changes:**
- Updated raiser queries to use `whereHas()` relationship instead of direct column comparison
- Changed from filtering by `pig_type` string column to querying through `pigType` relationship

**Before:**
```php
$fatteningRaisers = Raiser::where('pig_type', 'Fattening')->get(); // ❌ WRONG
```

**After:**
```php
$fatteningRaisers = Raiser::whereHas('pigType', function ($query) {
    $query->where('name', 'Fattening');
})->get(); // ✅ CORRECT
```

#### Fixed Dashboard View (`resources/views/pages/dashboard.blade.php`)
**Changes:**
- Updated pig type access from direct property to relationship
- Changed from `->pig_type` to `->pigType->name`

## Database Status

### Current Data
```
✅ Users: 1 (Test User)
✅ Raisers: 2 (John Doe, Maria Santos)
✅ Batches: 3 (Active production batches)
✅ Investments: 3 (Combined with batch investments)
✅ Investors: 3 (Active investors)
✅ Lifecycle Stages: 5
✅ Pig Types: 4
```

All migrations have been successfully applied and data has been seeded.

## Dashboard Features

The dashboard now displays:

### 1. Investment Summary Card
- **Start of Investment:** Total initial investment amount
- **Number of Hog Batch:** Count of active batches
- **Total Current Investment:** Current value of all investments
- **Number of Mortality:** Calculated from initial vs. current quantities
- **Expected Profit Return:** Projected profit from all batches

### 2. Investment Allocation Section
Shows percentage and amount allocation between:
- **Fattening Operations:** Meat production hogs
- **Sow Operations:** Breeding hogs

### 3. Raiser Lifecycle Section
Displays 5 selected raisers (3 fattening + 2 sow) with:
- Raiser name and pig type
- Production lifecycle stages
- Current stage status (completed, in-progress, pending)
- Timeline visualization
- Action buttons (View Profile)

## Running the Application

### Prerequisites
- PHP 8.1+
- MySQL 5.7+
- Composer
- Node.js (for frontend assets)

### Setup Steps

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Configuration**
   ```bash
   cp .env.example .env
   # Edit .env with your MySQL credentials
   php artisan key:generate
   ```

3. **Database Setup (Fresh Start)**
   ```bash
   php artisan migrate:fresh
   php artisan db:seed
   ```

4. **Build Frontend Assets**
   ```bash
   npm run build
   # or for development with watch:
   npm run dev
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```
   Server will run at: `http://127.0.0.1:8000`

### Login Credentials

**Admin Account:**
- Email: `test@example.com`
- Password: (auto-generated via factory)

**Cashier Account:**
- Email: `cashier@piggytrunk`
- Password: `cashier2027`

## API Endpoints

All protected endpoints require authentication via the `admin.auth` or `cashier.auth` middleware.

### Admin Dashboard Routes
- `GET /` - Main dashboard
- `GET /raisers` - List all raisers
- `POST /raisers` - Create new raiser
- `GET /investment` - Investment management
- `POST /investment` - Create new investment
- `GET /inventory` - Inventory management
- `GET /pos` - Point of sale system

### Cashier Routes
- `GET /cashier/retail` - Retail sales interface
- `GET /cashier/inventory` - Inventory view
- `GET /api/quick-sale/session` - Get current sales session
- `POST /api/quick-sale/add-item` - Add item to sale

### Quick Sale API (Shared)
- `GET /api/quick-sale/session` - Get session
- `POST /api/quick-sale/add-item` - Add item
- `PUT /api/quick-sale/item/{item}` - Update item
- `DELETE /api/quick-sale/item/{item}` - Remove item
- `POST /api/quick-sale/confirm` - Complete sale
- `POST /api/quick-sale/cancel` - Cancel sale

## Data Relationships

```
users (1) ──→ (many) raisers
           ──→ (many) other_records

pig_types (1) ──→ (many) raisers
           ──→ (many) batches

raisers (1) ──→ (many) batches

batches (1) ──→ (many) investments
         ──→ (many) batch_stage_history

batch_lifecycle_stages (1) ──→ (many) batch_stage_history

investors (many) ──→ (many) investments (through investment_investors)
```

## Performance Optimizations

### Database Queries in Dashboard
- Uses `whereHas()` for efficient relationship queries
- Includes proper indexing on foreign keys
- Aggregates calculations at query level (SUM, COUNT)
- Limits result sets (3 fattening, 2 sow raisers)

### Recommended Indexes
All tables have indexes on:
- Foreign key columns
- Status columns (for filtering)
- soft_delete timestamps
- Date ranges for reporting

## Troubleshooting

### Common Issues

**1. "SQLSTATE[HY000]: General error: 1005 Can't create table"**
- Cause: Missing referenced table or foreign key constraint issue
- Solution: Ensure migrations run in correct order (users table must be first)

**2. "Unknown column 'lifecycle_stage_id'"**
- Cause: Incorrect column name in batch_stage_history
- Solution: Use `lifecycle_stage_id` not `stage_id`

**3. "Field 'sequence' doesn't have a default value"**
- Cause: Required field without value in migration
- Solution: Include all required fields in seeder with valid values

**4. "SQLSTATE[23000]: Integrity constraint violation 1062 Duplicate entry"**
- Cause: Duplicate email when re-seeding
- Solution: Use `migrate:fresh` to clear database before seeding

### Database Reset

To completely reset the database:
```bash
php artisan migrate:fresh --seed
```

This will:
1. Drop all tables
2. Re-run all migrations
3. Seed with fresh data

## Files Modified

1. ✅ `database/migrations/2024_01_01_000001_create_users_table.php` (Created)
2. ✅ `database/seeders/PigTypeSeeder.php` (Created)
3. ✅ `database/seeders/SampleDataSeeder.php` (Created)
4. ✅ `database/seeders/DatabaseSeeder.php` (Updated to call all seeders)
5. ✅ `app/Models/Raiser.php` (Fixed fillable attributes)
6. ✅ `app/Http/Controllers/DashboardController.php` (Fixed queries)
7. ✅ `resources/views/pages/dashboard.blade.php` (Fixed pig_type access)

## Next Steps

1. **Customize Dashboard:** Update dashboard colors and branding
2. **Add More Data:** Extend seeders with production-like data
3. **Implement Reporting:** Add export and reporting features
4. **Mobile Support:** Optimize for mobile cashier operations
5. **Real-time Updates:** Implement WebSocket for live inventory updates

## Support

For issues or questions about the database setup, refer to:
- Laravel Documentation: https://laravel.com/docs
- Model Relations: Use eager loading with `with()` for performance
- Database Queries: Use Query Builder or Laravel ORM

---
**Last Updated:** April 19, 2026
**System Status:** ✅ Active and Ready
