# Smart Poultry Farm Management System

Laravel-based poultry management system for tracking chick batches, weekly growth, egg production, feed and medicine stock, mortality, sales, expenses, and profit.

## What is included

- Landing page for guests
- Session login and registration
- Role support for `admin` and `worker`
- Dashboard with farm totals, alerts, and growth summary
- Record forms for batches, growth, eggs, stock, mortality, sales, and expenses
- Admin user role management on the dashboard
- Demo seed data for fast evaluation

## Demo accounts

- Admin: `admin@chickenfarm.test`
- Worker: `worker@chickenfarm.test`
- Password: `password`

## Setup

```bash
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

## Main routes

- `/` guest landing page
- `/login` login form
- `/register` worker registration form
- `/dashboard` farm dashboard

## Notes

- New users register as workers by default.
- Admin users can update roles from the dashboard.
- The dashboard is designed as a single operational console so farmers can enter and review records quickly from desktop or mobile.