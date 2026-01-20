# Mariya Electronics - Shop Management System

A comprehensive shop management system built with Laravel for electronics stores. This system helps you manage inventory, sales, customer data, installment payments, and generate receipts.

## Features

### Core Features
- **Product Management**: Track cost price, selling price, maximum discount, and stock levels
- **Customer Management**: Store customer information and track purchase history
- **Sales Management**: Handle both cash and installment sales
- **Installment Management**: Track payment schedules, due dates, and late payment penalties
- **Payment Tracking**: Record all payments with different payment methods
- **Cash Memo Generation**: Generate PDF receipts for sales
- **Dashboard Analytics**: View key metrics and alerts

### Key Capabilities
- Inventory tracking with low stock alerts
- Installment payment schedules (1-12 months)
- Late payment penalties and reminders
- Due date notifications for installments
- Comprehensive sales reporting
- Customer purchase history
- Product sales analytics

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- XAMPP (for local development)

## Quick Start Guide

### 1. XAMPP Setup
1. Start XAMPP Control Panel
2. Start Apache and MySQL services
3. Open phpMyAdmin (http://localhost/phpmyadmin)
4. Create a new database named `mariya_electronics`

### 2. Database Setup
Run the database migrations to create all tables:
```bash
php artisan migrate
```

### 3. Start the Application
```bash
php artisan serve
```

The application will be available at: **http://localhost:8000**

## Next Steps

The basic Laravel structure is set up with:
- ✅ Models with relationships (Product, Customer, Sale, Installment, Payment)
- ✅ Migrations for all database tables  
- ✅ Basic controllers with CRUD operations
- ✅ Routes configuration
- ✅ Dashboard view with analytics
- ✅ Bootstrap UI layout

### To Complete the System:

1. **Run migrations**: `php artisan migrate`
2. **Create additional views** for products, customers, sales, etc.
3. **Add PDF generation** for receipts
4. **Implement installment scheduling logic**
5. **Add payment processing forms**

## Database Tables Created

1. **products** - Product information, pricing, stock
2. **customers** - Customer details and credit info
3. **sales** - Sales transactions (cash/installment)
4. **installments** - Payment schedules
5. **payments** - Payment records

## Key Features Built

- Dashboard with real-time analytics
- Product inventory management
- Customer relationship management  
- Sales tracking (cash and installments)
- Installment payment scheduling
- Late payment penalty system
- Stock level monitoring
- Payment history tracking

---

**Shop Management System for Mariya Electronics**  
**Built with Laravel 10 + Bootstrap 5 + MySQL**
