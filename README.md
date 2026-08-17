# TutorConnect — Professional Tutor Marketplace Platform

**TutorConnect** is a full-featured, multi-role tutor marketplace platform built with **Laravel 12**, **Blade**, **Tailwind CSS**, and **Alpine.js**. It enables students to discover verified subject mentors, schedule 1-on-1 online sessions, complete sandbox checkout, access study materials, and submit verified session reviews.

---

## 🚀 Tech Stack

- **Backend Framework**: Laravel 12 (PHP 8.2+)
- **Frontend / Templating**: Laravel Blade + Alpine.js (reactive micro-interactions, modal management, live slot querying)
- **Styling & Design System**: Tailwind CSS with tailored color tokens (Deep Blue `#1e40af`, Emerald `#059669`, Slate `#1e293b`)
- **Typography**: Google Fonts (*Inter* for body typography, *Poppins* for headings)
- **Database**: MySQL (fully normalized schema with 12 foreign-key ordered migrations)
- **Authentication**: Multi-role Laravel Breeze (Student, Tutor, Admin)
- **Payment Gateway**: Stripe Sandbox Test Mode (`is_demo = true`, simulated test cards)
- **Icons**: FontAwesome 6 Pro CDN

---

## 👥 Platform User Roles & Feature Matrix

### 1. 🎓 Student Experience
- **Public Directory & Filter**: Search tutors by subject, price range in PKR, availability day, and star ratings.
- **Tutor Profile**: View education history, biography, verified badges, weekly availability schedule, and reviews.
- **Booking Wizard**: Subject selection, date picker with tutor schedule awareness, AJAX time-slot lookup, and order summary.
- **Stripe Sandbox Checkout**: Pre-filled test card helper (`4242 4242 4242 4242`) with USD equivalent conversion.
- **Student Dashboard**: Real-time stats, next 3 upcoming sessions, and personalized mentor recommendations matching `subjects_needed`.
- **Verified Reviews**: 5-star rating submission form with Alpine.js interactive star picker for completed sessions.
- **Study Materials & Chat**: 1-on-1 direct messaging and downloadable study resources.

### 2. 👨‍🏫 Tutor Workspace
- **Tutor Dashboard**: Profile completion progress bar (0–100%), 4 KPI cards, upcoming 5 sessions table, and recent review quotes.
- **Profile & Rates Editor**: Multi-select subject checkboxes (Math, Physics, English, Chemistry, CS, Biology, Urdu, Islamiat, History, Geography), PKR hourly rate, education background, bio (min 50 chars), and photo upload (`storage/app/public/photos`).
- **Weekly Availability Builder**: 7-day schedule matrix (Monday–Sunday) with active hours toggles.
- **Booking Management**: Confirm incoming requests (`pending` &rarr; `confirmed`), complete sessions (`confirmed` &rarr; `completed`), and decline requests.
- **Reviews Breakdown**: 5-star rating distribution chart and feedback feed.

### 3. 🛡️ Admin Console
- **Executive Overview**: Total Users, Total Tutors, Total Students, Total Bookings, Demo Revenue, and CSS monthly bookings chart.
- **User Governance**: Multi-role filtering, search, account activation/deactivation toggle, and deletion.
- **Tutor Verifications**: Review educator credentials and grant/revoke official **Verified Educator** badges.
- **Bookings Audit**: System-wide appointment inspection, date range filter, and administrative force-cancellation.
- **Review Moderation**: Audit student comments and delete inappropriate entries with automatic tutor rating recalculation.
- **Analytics & Growth**: Subject demand progress bars, tutor leaderboards, and monthly user acquisition tables.

---

## 🔑 Demo Seed Accounts (Instant Testing)

The platform is pre-seeded with realistic demonstration accounts:

| Role | Email | Password | Description / Focus |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@tutorconnect.com` | `admin123` | Master administrator console with all permissions |
| **Tutor** | `ahmed.khan@tutorconnect.com` | `password` | Mathematics & Calculus Specialist (PKR 3,500/hr) |
| **Tutor** | `dr.sarah@tutorconnect.com` | `password` | Chemistry & Medical Entry Test Specialist (PKR 4,500/hr) |
| **Tutor** | `fatima.noor@tutorconnect.com` | `password` | IELTS 8.5 Master Coach & English (PKR 3,000/hr) |
| **Tutor** | `usman.farooq@tutorconnect.com` | `password` | Physics & Mechanics Specialist (PKR 4,000/hr) |
| **Tutor** | `bilal.tariq@tutorconnect.com` | `password` | Python & Computer Science Specialist (PKR 4,000/hr) |
| **Student** | `student@tutorconnect.com` | `password` | A-Levels Cambridge student with active bookings |

---

## 💳 Stripe Sandbox Test Payment Credentials

All payment flows operate strictly in **Sandbox / Demonstration Mode** (`is_demo = true`):

- **Test Card Number**: `4242 4242 4242 4242`
- **Expiration Date**: Any future date (e.g., `12/28`)
- **Security Code (CVV)**: Any 3 digits (e.g., `123`)
- **Auto-Fill Helper**: Click the **Auto-Fill Test Card** button on the checkout page to instantly populate test card values.

---

## 🛠️ Installation & Setup Guide

Follow these steps to run TutorConnect locally:

```bash
# 1. Clone or extract the repository
cd TutorConnect-laraval-main

# 2. Install PHP and Composer dependencies
composer install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Configure MySQL database in .env:
# DB_DATABASE=tutorconnect
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Run fresh database migrations and populate seed data
php artisan migrate:fresh --seed

# 6. Create symbolic link for public photo & material storage
php artisan storage:link

# 7. Start the local development server
php artisan serve
```

Open your browser and navigate to `http://localhost:8000`.

---

## 📱 Mobile Responsiveness & Polish

- Fully responsive across desktop (1440px+), tablet (768px), and mobile (375px) viewports.
- Responsive off-canvas slide-out drawers powered by Alpine.js.
- Clean flash alerts (`x-alert`) displaying success, error, and informational messages.
- Custom branded `404 Not Found` and `403 Unauthorized` error pages.

---

## 📄 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
