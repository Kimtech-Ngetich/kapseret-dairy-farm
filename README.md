# Kapseret Dairy Farm Management System

## Project Overview
The Kapseret Dairy Farm Management System is a web-based application designed to help manage daily dairy farm operations. It provides a central place to record and manage cows, milk production, breeding, animal health, feed usage, employees, sales, and reports.

The system aims to reduce paperwork, improve record accuracy, and make farm information easier to access.

## Features
- **Dashboard:** View an overview of farm activities.
- **Cow Management:** Add, view, edit, and delete cow records.
- **Breeding Management:** Maintain breeding records.
- **Milk Production:** Record and manage milk production information.
- **Animal Health:** Maintain treatment and health records.
- **Feed Management:** Manage feed records and feed usage.
- **Employee Management:** Maintain employee information.
- **Sales Management:** Record and manage farm sales.
- **Reports:** Generate reports for farm activities.
- **User Authentication:** Login, registration, and logout functionality.
- **Settings:** Manage system settings and user-related functions.

## Technologies Used
- PHP
- MySQL
- HTML
- CSS
- JavaScript
- Apache and MySQL through XAMPP

## Requirements
- Windows, Linux, or another compatible operating system
- XAMPP or another PHP/MySQL development environment
- A web browser
- A MySQL database named `kapseret_dairy_farm`

## Installation and Setup
1. Install and start XAMPP.
2. Copy or clone this project into the XAMPP `htdocs` directory.
3. Start Apache and MySQL from the XAMPP Control Panel.
4. Open phpMyAdmin in your browser.
5. Create a database named `kapseret_dairy_farm`.
6. Import the project's database SQL file if one is available. If no SQL file exists, create the required tables according to the application's database structure.
7. Configure your local database connection in `config/database.php`.
8. Open the application in your browser using:

   `http://localhost/kapseret-dairy-farm/`

## Project Structure
- `assets/` — CSS, JavaScript, and other assets
- `auth/` — Authentication pages
- `breeding/` — Breeding records
- `config/` — Application configuration
- `cows/` — Cow management
- `dashboard/` — Dashboard
- `employees/` — Employee management
- `feed/` — Feed management
- `feed_usage/` — Feed usage records
- `health/` — Animal health records
- `includes/` — Shared application components
- `milk/` — Milk production records
- `reports/` — Reports
- `sales/` — Sales management
- `settings/` — System settings

## Security Notice
Database configuration files and environment files containing credentials should not be committed to a public repository. Configure your database credentials securely for each environment.

## Project Status
This project is maintained as a dairy farm management application. Features and documentation may be updated as development continues.

## License
No license has been specified yet. All rights are reserved by the copyright holder unless a license is added.
