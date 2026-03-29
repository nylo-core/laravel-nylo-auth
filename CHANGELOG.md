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