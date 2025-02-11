# Laravel REST API with JWT Authentication, MySQL, and Redis Caching

## Introduction
This project is a REST API for user authentication and post management using **Laravel**, **JWT Authentication**, **MySQL**, and **Redis** caching. The application also includes unit tests and a minimal Docker setup for easy deployment.

## Features
- **User Registration and Login** with JWT authentication  
- CRUD operations for **Posts**  
- **Redis Caching** for improved performance on data retrieval for posts  
- **Unit Tests** for core API routes  
- **Docker Setup** for Laravel, MySQL, and Redis  

## Prerequisites
Ensure you have the following installed on your system:
- PHP 8.x  
- Composer  
- MySQL  
- Redis  
- Docker  

## Setup Instructions

### Step 1: Clone the Repository

 - git clone "repository-url"
 - cd "repository-folder"

## Install Dependencies
 - composer install
## Run Migrations
 - php artisan migrate
## Start the Application
 - php artisan serve (for development/ test)

## API Documentation

### Authentication Endpoints

| Method | Endpoint      | Description                                     |
|--------|---------------|-------------------------------------------------|
| POST   | /api/register | Register a new user (name, email, password)     |
| POST   | /api/login    | Authenticate user and return JWT token          |

### Post Management Endpoints

| Method | Endpoint        | Description                                   |
|--------|-----------------|-----------------------------------------------|
| GET    | /api/posts      | Fetch all posts (cached in Redis)             |
| POST   | /api/posts      | Create a new post (title, content, user_id)   |
| GET    | /api/posts/{id} | Fetch a single post (cached in Redis)         |
