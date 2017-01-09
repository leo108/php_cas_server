# PHP CAS Server

PHP CAS Server is a PHP implementation of CAS Server Protocol based on Laravel.

[中文文档](https://github.com/leo108/php_cas_server/blob/master/readme_zh.md)

## Features

* [CAS protocol](https://apereo.github.io/cas/4.2.x/protocol/CAS-Protocol-Specification.html) v1/v2/v3 (proxy is supported now!).
* User management, including adding/editing/searching users, enable/disable users, set/unset as administrator.
* Service management, including adding/editing/searching services, enable/disable services.
* I18n, support English and Chinese out of box, you can add language as your need.
* Customize login methods, support email + password by default, you can add custom login methods by plugins. You can also disable email login by settings.

## Requirements

* PHP >= 5.5.9

## Installation

### By composer (Recommend)

1. `composer create-project leo108/php_cas_server php_cas_server dev-master`
2. `npm install` or `yarn`
3. `gulp`

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
|CAS_PROXY_GRANTING_TICKET_EXPIRE|`7200`|CAS proxy-granting ticket expire time, in seconds|
|CAS_PROXY_GRANTING_TICKET_LEN|`64`|CAS proxy-granting ticket length, it's recommend at least 64|
|CAS_PROXY_GRANTING_TICKET_IOU_LEN|`64`|CAS proxy-granting ticket IOU length, it's recommend at least 64|
|CAS_VERIFY_SSL|`true`|Whether to check ssl when calling pgt url|
|CAS_SERVER_ALLOW_RESET_PWD|`true`|allow user reset password by email|
|CAS_SERVER_ALLOW_REGISTER|`true`|allow user register|
|CAS_SERVER_DISABLE_PASSWORD_LOGIN|`false`|disable password login|
|CAS_SERVER_NAME|`Central Authentication Service`|The site name of your CAS Server|

### Setup behind reverse proxy

|Field|Default Value|Description|
|-----|-----|---|
|TRUSTED_PROXIES|`127.0.0.1`|The IP of reserve proxy servers, separated by comma(`,`), you can specific IP or use s subnet such as `127.0.0.1` and `127.0.0.1/24`, configurations below take effect only when visiting IP in this list|
|TRUSTED_HEADER_CLIENT_IP|`X_FORWARDED_FOR`|User's real IP is stored in this request header|
|TRUSTED_HEADER_CLIENT_HOST|`X_FORWARDED_HOST`|The host user visited is stored in this request header|
|TRUSTED_HEADER_CLIENT_PROTO|`X_FORWARDED_PROTO`|The http protocol user used is stored in this request header|
|TRUSTED_HEADER_CLIENT_PORT|`X_FORWARDED_PORT`|The port user visited is stored in this request header|

## Initial database and create administrator

Execute `php artisan migrate` at the root directory of this project to initial database.

Execute `php artisan make:admin --password=yourpassword` to create an administrator account.

## License

[MIT](http://opensource.org/licenses/MIT).
