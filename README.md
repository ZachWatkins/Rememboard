# HomeDash

A dashboard for the family computer.

Built with Laravel, InertiaJS, and React.

-   [Feature Roadmap](#feature-roadmap)
-   [Local System Requirements](#system-requirements)
-   [Installation](#installation)
-   [Features](#features)
-   [Routes](#routes)
-   [Additional Documentation](#additional-documentation)
-   [About Laravel](#about-laravel)

## Feature Roadmap

These are in no particular order the further down the list you go. The first four are the most important.

-   [ ] Home page with layout and navigation
-   [ ] "Days until" widget for major events (birthdays, holidays, trips, etc.)
-   [ ] "Is it too loud in here" widget for quiet time on the weekends.
-   [ ] Map widget for showing our kids all the places we've traveled together
-   [ ] Daily task or chore list for each user
-   [ ] "What's for breakfast" widget
-   [ ] Weather widget
-   [ ] Calendar widget
-   [ ] "What's for dinner" widget

## Local System Requirements

PHP and Composer are required to install and run this project. You can use my installer scripts (Mac or Linux: [./bin/install-php](./bin/install-php)) (Windows: [./bin/install-php.bat](./bin/install-php.bat)) or see the links below for official instructions:

-   [PHP](https://www.php.net/downloads.php)
-   [Composer](https://getcomposer.org/download/)

## Installation

```shell
git clone https://github.com/zachwatkins/laravel-template
cd laravel-template
npm install
composer create-project
composer run dev
```

## Features

Laravel first-party packages and features:

1. **Breeze (Laravel Package)** for user registration, login, authentication, and profile management. Also includes PestPHP tests for authentication features.
2. **React with TypeScript and Inertia.js** for building single-page applications with type safety.
3. **Tests** for peace of mind.

## Routes

-   [Public Web Routes](#public-web-routes)
-   [Guest Web Routes](#guest-web-routes)

### Public Web Routes

| Verb | URI | Action | Route Name
| GET | `/` | view | welcome

### Guest Web Routes

| Verb | URI | Action | Route Name
| GET | `/register` | create | register
| POST | `/register` | store | -
| GET | `/login` | create | login
| POST | `/login` | store | -
| GET | `/forgot-password` | create | password.request
| POST | `/forgot-password` | store | password.email
| GET | `/reset-password` | create | password.reset
| POST | `/reset-password` | store | password.update

### Authenticated Web Routes

| Verb | URI | Action | Route Name
| GET | `/verify-email` | create | verification.notice
| GET | `/verify-email/{id}/{hash}` | create | verification.verify
| POST | `/verify-email/{id}/{hash}` | store | -
| POST | `/verify-email-notification` | store | verification.send
| GET | `/confirm-password` | create | password.confirm
| POST | `/confirm-password` | store | -
| PUT | `/password` | update | password.update
| POST | `/logout` | destroy | logout
| GET | `/dashboard/` | view | dashboard
| GET | `/profile` | view | profile.edit
| PATCH | `/profile` | update | profile.update
| DELETE | `/profile` | destroy | profile.destroy

## Additional Documentation

[React Documentation](https://react.dev)

## About Laravel

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

### Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.
