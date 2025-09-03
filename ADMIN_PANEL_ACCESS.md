# 🔐 Evoka Admin Panel Access Guide

## 🎯 Admin Panel Credentials

**✅ Admin User Created Successfully!**

### Login Credentials:
- **Email:** `admin@evoka.com`
- **Password:** `admin123`
- **Account Type:** `admin`

## 🌐 Admin Panel URLs

### Main Admin Panel:
- **URL:** `http://localhost:8000/home`
- **Login URL:** `http://localhost:8000/login`

### Admin Sections:
- **Users Management:** `http://localhost:8000/admin/users`
- **Events Management:** `http://localhost:8000/admin/events`
- **Event Participation:** `http://localhost:8000/admin/events_participation`

## 🚀 How to Access

### Step 1: Start the Backend Server
```bash
cd "evoka - naveen"
php artisan serve
```
Server runs at: `http://localhost:8000`

### Step 2: Access Admin Panel
1. Open browser and go to: `http://localhost:8000/login`
2. Enter credentials:
   - **Email:** `admin@evoka.com`
   - **Password:** `admin123`
3. Click "Login"

### Step 3: Navigate Admin Sections
After login, you'll be redirected to the dashboard. Use the sidebar to access:
- **Users** - Manage all registered users
- **Events** - Manage all events (approve/reject, feature, delete)
- **Event Participation** - Manage event bookings and participation status

## 📊 Admin Panel Features

### Users Management (`/admin/users`)
- ✅ View all registered users
- ✅ Delete users
- ✅ View user details (name, email, account type, etc.)

### Events Management (`/admin/events`)
- ✅ View all events
- ✅ Approve/Reject events
- ✅ Feature/Unfeature events
- ✅ Delete events
- ✅ Update event status

### Event Participation (`/admin/events_participation`)
- ✅ View all event bookings
- ✅ Update participation status (pending/approved/rejected)
- ✅ Manage participant approvals

## 🔧 Admin Panel Capabilities

### Event Management:
- **Status Control:** Change event status (pending → approved → completed)
- **Feature Control:** Mark events as featured
- **Email Notifications:** Automatic emails when events are approved
- **Bulk Operations:** Delete multiple events

### User Management:
- **User Overview:** Complete user information display
- **Account Types:** View different user account types
- **User Deletion:** Remove users from the system

### Participation Management:
- **Booking Oversight:** Monitor all event bookings
- **Status Updates:** Approve/reject participant requests
- **Event Capacity:** Track participation limits

## 🎯 Perfect for Testing Multi-Participant Booking

This admin panel is **ideal for testing your multi-participant booking feature** because you can:

1. **Create test events** through the frontend
2. **View them in admin panel** at `/admin/events`
3. **Approve events** to make them visible
4. **Monitor bookings** at `/admin/events_participation`
5. **See all participant data** when users book multiple tickets

## 🔄 Integration with Your Frontend

The admin panel works seamlessly with your React frontend:
- Events created in frontend appear in admin panel
- Bookings made in frontend show up in participation management
- Status changes in admin affect frontend display

## 🚨 Troubleshooting

### Can't Access Admin Panel?
1. **Check server is running:** `php artisan serve`
2. **Verify URL:** Use `http://localhost:8000/login`
3. **Check credentials:** `admin@evoka.com` / `admin123`
4. **Clear browser cache** if login issues persist

### Admin Panel Not Loading?
1. **Check Laravel logs:** `storage/logs/laravel.log`
2. **Verify database:** Ensure migrations ran successfully
3. **Check .env:** Verify database connection

## 🎉 Ready to Test!

Your Evoka backend is now fully set up with:
- ✅ **Backend API** running at `http://localhost:8000`
- ✅ **Admin Panel** accessible with credentials above
- ✅ **Database** with all tables created
- ✅ **Admin User** ready for testing

**Next Steps:**
1. Access the admin panel using the credentials above
2. Test your multi-participant booking feature
3. Monitor bookings through the admin interface
4. Verify data flow from frontend to backend

---

**🎯 Admin Panel URL:** `http://localhost:8000/login`  
**🔑 Login:** `admin@evoka.com` / `admin123`
