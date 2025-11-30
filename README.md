<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## ERD (Aplikasi Booking Fasilitas)

```mermaid
erDiagram
    USERS ||--o{ BOOKINGS : makes
    USERS ||--o{ ROOMS : manages
    ROOMS ||--o{ FACILITIES : owns
    CATEGORIES ||--o{ FACILITIES : groups
    FACILITIES ||--o{ BOOKINGS : reserved
    FACILITIES ||--o{ REPAIR_REPORTS : has
    USERS ||--o{ REPAIR_REPORTS : submits
    BOOKINGS ||--o| USERS : approved_by

    USERS {
        int id
        string name
        string email
        enum role
        int room_id FK
    }
    ROOMS {
        int id
        string name
        string code
        int user_id FK
    }
    CATEGORIES {
        int id
        string name
        string type
    }
    FACILITIES {
        int id
        string name
        int room_id FK
        int category_id FK
        int capacity
        int unit
    }
    BOOKINGS {
        int id
        int user_id FK
        int facility_id FK
        datetime start_time
        datetime end_time
        enum status
        int approved_by FK
        bool checked_in
        bool checked_out
    }
    REPAIR_REPORTS {
        int id
        int user_id FK
        int facility_id FK
        string description
        enum status
    }
```

## UML (Alur Booking & Check-in/Out)

```mermaid
flowchart LR
    A[User (Siswa/Guru non-PJ)] -->|buat booking| B[BookingController.store]
    B --> C[(Booking pending)]
    Admin[Admin/Guru PJ] -->|approve/reject| D[BookingController.approve/reject]
    D -->|approved| E[(Booking approved)]
    A -->|cek window -30 sd -25 menit| F{Window buka?}
    F -- ya --> G[BookingController.checkIn\nstatus -> active, checked_in=true]
    F -- tidak --> H[(Menunggu / Auto-cancel bila window lewat)]
    Scheduler[Command bookings:cancel-late-checkins] --> H
    H -->|window lewat & belum check-in| X[(Status cancelled)]
    G --> I{Waktu selesai?}
    I -- belum --> G
    I -- sudah --> J[BookingController.complete\nstatus -> completed, checked_out=true]
    Admin -->|pantau & check-out jika perlu| J
```

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
