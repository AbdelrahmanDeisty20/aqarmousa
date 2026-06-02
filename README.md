# 🏠 Propix-8 Real Estate Management System

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-4.x-yellow.svg)](https://filamentphp.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE.md)

**Propix-8** is a state-of-the-art Real Estate Management System designed for high performance, scalability, and seamless user experience. Built with **Laravel 12** and **Filament PHP 4**, it follows a strict **SOLID architecture** and clean code practices.

---

## 🏗 Architectural Excellence (SOLID & Clean Code)

The project is built with maintainability in mind, utilizing advanced design patterns:
- **SOLID Principles:** Decoupled business logic ensuring each component has a single responsibility.
- **Service Layer Pattern:** Implementation of dedicated services (e.g., `PaymentService`, `UnitService`, `MaintenanceManagementService`) to handle complex logic outside of controllers.
- **Repository-like Resource Management:** Optimized API resources (`UnitListResource`, `UnitResource`) for blazing-fast responses.
- **Dependency Injection:** Extensive use of DI for better testability and loosely coupled components.

---

## 🚀 Core Features

### 💳 Paymob Payment Integration
A fully integrated, secure payment gateway using **Paymob**:
- Automated transaction lifecycle (Initiation → Iframe → Callback).
- Real-time unit status synchronization (e.g., auto-marking units as "Sold" or "Rented" upon successful payment).
- Transaction logging and auditing.
- Admin notifications for successful payments.

### 💎 Powerful Admin Dashboard (Filament PHP)
A world-class administrative interface featuring:
- **Comprehensive Resource Management:** Units, Cities, Compounds, Developers, Amenities, and Banners.
- **Arabic-First Design:** Complete RTL support with a seamless language switcher using `filament-language-switch`.
- **Role-Based Access Control (RBAC):** Granular permissions powered by `spatie/laravel-permission` and `filament-shield`.
- **Media Hub:** Advanced handling of unit images and video processing using FFmpeg.

### 🏢 Specialized Unit Management
- **Intelligent API Layer:** Tailored responses for list views vs. detailed views.
- **Advanced Filtering Engine:** Multi-criteria search (City, Compound, Price, Area, Ownership, etc.).
- **Live Search:** High-relevance global search across the entire ecosystem.
- **Quality Control:** Approval/Rejection workflow for seller-submitted units.

### 🛠 Extended Ecosystem Features
- **Maintenance Management:** Full lifecycle for maintenance services and bookings.
- **Viewing Bookings:** Seamless scheduling for property viewings.
- **Interaction System:** Integrated user reviews, ratings, and a personalized favorites list.
- **Content CMS:** Manage FAQs, dynamic banners, testimonials, and custom pages directly from the dashboard.
- **Communication Hub:** Unified contact and messaging system.

### 🛠 Technical Highlights
- **Laravel 12 & PHP 8.2+:** Utilizing latest language features and performance enhancements.
- **Sanctum Authentication:** Secure API token management for mobile and web frontends.
- **FFmpeg Integration:** Automated video transcoding for unit media.
- **Localization:** Robust i18n support for English and Arabic.

---

## 🛠 Tech Stack

- **Backend:** Laravel 12.x
- **Admin Panel:** Filament PHP 4.x
- **Payment Gateway:** Paymob (Accept)
- **Database:** MySQL 8.x
- **Auth:** Laravel Sanctum
- **Tools:** Spatie Permission, Laravel FFmpeg, DomPDF, OpenSpout, PHPSpreadsheet.

---

## 📦 Installation & Setup

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/salah3122001/Propix-8-Real-Esatae-Website.git
   cd Propix-8-Real-Esatae-Website
   ```

2. **Initialize Project:**
   ```bash
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Configuration:**
   Configure your `.env` file with your database credentials and Paymob API keys:
   ```env
   PAYMOB_API_KEY=your_key
   PAYMOB_HMAC=your_hmac
   PAYMOB_INTEGRATION_ID=your_id
   PAYMOB_IFRAME_ID=your_iframe_id
   ```

4. **Migration & Seeding:**
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

5. **Run the Server:**
   ```bash
   php artisan serve
   ```

---

## 📄 License
Distributed under the MIT License. See `LICENSE.md` for more information.

---
**Developed with ❤️ for the modern Real Estate Market.**
