# 🚀 Evoka Backend Setup Guide

## 📋 Overview
This is the **actual Evoka backend** (Laravel 12) that matches your React frontend. It includes:
- ✅ Event management system
- ✅ User authentication (Google OAuth, Facebook, regular login)
- ✅ Event participation system
- ✅ Rating and commenting system
- ✅ Chat functionality
- ✅ Subscription system
- ✅ PayPal integration

## 🛠️ Prerequisites
- **PHP 8.2+**
- **Composer** (PHP package manager)
- **MySQL** (or SQLite for easier setup)
- **Node.js** (for frontend assets)

## 📁 Project Structure
```
evoka - naveen/
├── app/
│   ├── Http/Controllers/     # API controllers
│   ├── Models/              # Database models
│   └── Mail/                # Email templates
├── database/
│   ├── migrations/          # Database schema
│   └── database.sqlite      # SQLite database (if using)
├── routes/
│   └── api.php             # API routes
├── .env                    # Environment configuration
└── composer.json           # PHP dependencies
```

## 🚀 Quick Setup (Recommended)

### Option 1: SQLite (Easiest)
```bash
# 1. Navigate to backend directory
cd "evoka - naveen"

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
copy .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create SQLite database
New-Item -ItemType File -Path "database\database.sqlite" -Force

# 6. Update .env for SQLite
# Change these lines in .env:
# DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite

# 7. Run migrations
php artisan migrate

# 8. Start the server
php artisan serve
```

### Option 2: MySQL (Production-like)
```bash
# 1. Navigate to backend directory
cd "evoka - naveen"

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
copy .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create MySQL database
# Create database named 'evoka' in your MySQL server

# 6. Update .env for MySQL (already configured)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=evoka
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Run migrations
php artisan migrate

# 8. Start the server
php artisan serve
```

## 🔧 Configuration

### Environment Variables (.env)
```env
APP_NAME=Evoka
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (choose one)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# OR for MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=evoka
# DB_USERNAME=root
# DB_PASSWORD=

# Google OAuth (configure with your own credentials)
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/api/google/callback

# PayPal (configure with your own credentials)
PAYPAL_CLIENT_ID=your_paypal_client_id_here
PAYPAL_SECRET=your_paypal_secret_here
PAYPAL_BASE_URL=https://api-m.sandbox.paypal.com

# Frontend URL for OAuth redirects
FRONTEND_URL=https://evoka-frontend.vercel.app/
```

## 📊 Database Schema

### Key Tables:
- **users** - User accounts
- **events** - Event information
- **event_participations** - Event bookings
- **event_ratings** - Event ratings
- **event_comments** - Event comments
- **event_chats** - Event chat messages
- **subscriptions** - User subscriptions

### Current Event Participation Structure:
```sql
event_participations:
- id
- user_id (foreign key)
- event_id (foreign key)
- status (pending/approved/rejected)
- status_reason
- timestamps
```

## 🔌 API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/register` - User registration
- `POST /api/logout` - User logout
- `GET /api/user` - Get current user
- `GET /api/google/callback` - Google OAuth callback
- `GET /api/facebook/callback` - Facebook OAuth callback

### Events
- `GET /api/events` - Get all events
- `GET /api/events/{id}` - Get single event
- `POST /api/events/{id}/participate` - Book event
- `POST /api/events/{id}/rate` - Rate event
- `GET /api/events/{id}/my-ratings` - Get user ratings
- `POST /api/events/{id}/comments` - Add comment
- `POST /api/events/{id}/send_chat` - Send chat message
- `GET /api/events/{id}/chat` - Get chat messages

### User Management
- `GET /api/my-events` - Get user's events
- `GET /api/my-participation` - Get user's participations
- `POST /api/create-event` - Create new event
- `POST /api/update-profile` - Update user profile

## 🧪 Testing the Backend

### 1. Start the Backend Server
```bash
cd "evoka - naveen"
php artisan serve
```
Server will run at: `http://localhost:8000`

### 2. Test API Endpoints
```bash
# Test if server is running
curl http://localhost:8000/api/events

# Test user registration
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123"}'
```

### 3. Frontend Integration
Update your frontend `axios.js` to point to:
```javascript
baseURL: 'http://localhost:8000/api'
```

## 🔄 Multi-Participant Booking Integration

The current backend supports single-user participation. To support your multi-participant booking feature, you'll need to:

1. **Modify the participation endpoint** to accept multiple participants
2. **Update the database schema** to store participant details
3. **Handle payment processing** for multiple tickets

## 🚨 Troubleshooting

### Common Issues:

1. **"Could not open input file: artisan"**
   - Make sure you're in the correct directory: `evoka - naveen`

2. **Database connection errors**
   - Check your `.env` file database configuration
   - Ensure MySQL is running (if using MySQL)
   - Create the database file (if using SQLite)

3. **Composer install fails**
   - Ensure PHP 8.2+ is installed
   - Check Composer is installed: `composer --version`

4. **Migration errors**
   - Clear config cache: `php artisan config:clear`
   - Check database permissions

## 🎯 Next Steps

1. **Set up the backend** using the steps above
2. **Test the API endpoints** to ensure they work
3. **Update your frontend** to use the real backend instead of mocks
4. **Implement multi-participant booking** modifications
5. **Test the complete flow** from frontend to backend

## 📞 Support

If you encounter any issues:
1. Check the Laravel logs: `storage/logs/laravel.log`
2. Verify your `.env` configuration
3. Ensure all dependencies are installed
4. Check database connectivity

---

**Ready to start?** Follow the Quick Setup steps above! 🚀
