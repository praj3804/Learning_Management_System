# 📚 LMS - Single Course Learning Management System

## 🚀 Overview
This is a role-based Learning Management System (LMS) built using Laravel and MySQL. It allows institutions to deliver a single video-based course with controlled batch access, progress tracking, quiz evaluation, and certificate generation.

## ✨ Features

### 👨‍🎓 Student
- Register using batch token
- Watch course video
- Track completion progress
- Attempt quiz
- Download certificate upon passing

### 🛠️ Admin
- Create and manage batches
- Upload/manage course video
- Create quiz (MCQs)
- Track student progress and performance
- Manage certificate generation

## 🧱 Tech Stack
- Backend: Laravel (PHP)
- Frontend: Blade + Tailwind CSS
- Database: MySQL
- Server: XAMPP / Apache

## ⚙️ Installation

```bash
git clone https://github.com/your-username/lms-project.git
cd lms-project
composer install
cp .env.example .env
php artisan key:generate

## 🗄️ Database Setup

```bash
php artisan migrate:fresh --seed


##▶️ Run the Application

```bash
php artisan serve

Visit: http://127.0.0.1:8000


Developed as a real-world academic project.