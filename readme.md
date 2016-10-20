# PHP CAS Server

PHP CAS Server is a PHP implementation of CAS Server Protocol based on Laravel.

[中文文档](https://github.com/leo108/php_cas_server/wiki/%E4%B8%AD%E6%96%87%E6%96%87%E6%A1%A3)

## Features

* [CAS protocol](https://apereo.github.io/cas/4.2.x/protocol/CAS-Protocol-Specification.html) v1/v2/v3 without proxy, proxy related implements is under planning.
* User management, including adding/editing/searching users, enable/disable users, set/unset as administrator.
* Service management, including adding/editing/searching services, enable/disable services.
* I18n, support English and Chinese out of box, you can add language as your need.
* Customize login methods, support email + password by default, you can add custom login methods by plugins. You can also disable email login by settings.

## Requirements

* PHP >= 5.5.9

## Installation

### By composer (Recommend)

`composer create-project leo108/php_cas_server php_cas_server dev-master`

### By release tarballs

[Download Link](https://github.com/leo108/php_cas_server/releases)

## Configuration

If you install by tarball, you have to copy `.env.example` to `.env`, and then run `php artisan key:generate`

All settings are in `.env` file.

### Basic

|Field|Default Value|Description|
|-----|-----|---|
|APP_ENV|`local`|running environment，use `local` if in development, use `production` in production|
|APP_KEY|random value|left as is|
|APP_DEBUG|`true`|enable debug mode, set to `false` to disable|
|APP_LOG_LEVEL|`debug`|log level, `debug`/`info`/`notice`/`warning`/`error`/`critical`/`alert`/`emergency`|
|APP_URL|`http://localhost`|your app's url, needs `http(s)://` at the beginning|
|APP_LOCALE|`en`|language, support `en` and `cn` out of box|

### Database

You have to set all fields that begin with `DB_`, then run `php artisan migrate` to initial database schema.

### CAS Server

|Field|Default Value|Description|
|-----|-----|---|
|CAS_LOCK_TIMEOUT|`5000`|CAS ticket locking time, in milliseconds|
|CAS_TICKET_EXPIRE|`300`|CAS ticket expire time, in seconds|
|CAS_TICKET_LEN|`32`|CAS ticket length, it's recommend at least 32|
|CAS_SERVER_ALLOW_RESET_PWD|`true`|allow user reset password by email|
|CAS_SERVER_ALLOW_REGISTER|`true`|allow user register|
|CAS_SERVER_DISABLE_PASSWORD_LOGIN|`false`|disable password login|

## License

[MIT](http://opensource.org/licenses/MIT).
