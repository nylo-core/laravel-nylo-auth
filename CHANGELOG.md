## [2.1.0] - 2026-04-16

### Added
- Configurable middleware via `middleware.public` and `middleware.authenticated` config keys; custom middleware is appended to the built-in `throttle:*` / `auth:sanctum` middleware so the defaults always run first
- Per-route middleware via `middleware.routes` config key, keyed by full route name (e.g. `nylo.api.v1.register`); runs after built-in and group-level middleware for that route

## [2.0.0] - 2026-03-29

### Changed
- Bumped minimum PHP version to 8.2
- Upgraded to Laravel 11/12 support (dropped Laravel 10)
- Upgraded to Sanctum 4.x
- Upgraded to Pest 3, PHPUnit 11, Collision 8, Testbench 9/10
- Updated CI matrix: PHP 8.2–8.4, Laravel 11–12, `actions/checkout@v4`

## [1.3.0] - 2026-03-29

### Added
- Rate limiting system with configurable `RateLimiterContract` interface
- `PublicRateLimiter` default implementation (5 requests/min per IP) for login, register, and forgot-password routes
- `AuthenticatedRateLimiter` default implementation (60 requests/min per user) for authenticated routes
- Named rate limiters (`nylo-public` and `nylo-auth`) registered via service provider from config
- Throttle middleware applied to public and authenticated route groups
- `rate_limits` configuration key for swapping rate limiter classes
- `laravel/sanctum` as an explicit package dependency
- Laravel 13 support
- Comprehensive test suite for auth endpoints and rate limiter contracts

### Changed
- `register()` now uses `create()` instead of `updateOrCreate()` for correct registration behavior
- README rewritten with requirements, API endpoint table, configuration reference, and rate limiting documentation

### Fixed
- Fixed `forget-password` route name typo to `forgot-password`

## [1.2.1] - 2025-03-09

* Update AuthController

## [1.2.0] - 2025-02-26

* Laravel 12 support
* Add name to AuthController

## [1.0.1] - 2023-11-26

* Fix forgotPassword route

## [1.0.0] - 2023-11-25

* Initial release