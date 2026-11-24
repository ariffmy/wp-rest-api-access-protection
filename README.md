# AriffMY REST Security

![Version](https://img.shields.io/badge/version-2.2.0-blue)
![License](https://img.shields.io/badge/license-GPL--2.0%2B-green)
![WordPress](https://img.shields.io/badge/platform-WordPress-21759B)
![PHP](https://img.shields.io/badge/language-PHP-777BB4)
![MU Plugin](https://img.shields.io/badge/type-Must--Use%20Plugin-orange)

A lightweight WordPress Must-Use (MU) security plugin designed to restrict public REST API discovery, namespace exposure, and user enumeration while preserving authenticated REST API access.

Developed and maintained by **ariff.my**.

## Overview

WordPress exposes REST API discovery endpoints by default. While this is standard WordPress behaviour, publicly accessible API indexes may reveal registered routes, namespaces, and user information.

AriffMY REST Security reduces unnecessary information exposure without completely disabling the WordPress REST API.

Designed as a Must-Use plugin, it loads automatically and cannot be accidentally deactivated through the standard WordPress Plugins dashboard.

## Features

- Blocks anonymous access to the main REST API index.
- Automatically detects and restricts registered namespace indexes.
- Restricts public WordPress REST API user enumeration.
- Supports standard and query-based REST API URLs.
- Preserves authenticated REST API access.
- Preserves existing WordPress authentication and permission checks.
- Returns a custom HTTP 403 Forbidden response.
- Automatically loads as a Must-Use plugin.
- Cannot be deactivated through the standard WordPress Plugins dashboard.
- Requires no database tables or additional dependencies.
- No configuration required.
- Lightweight, single-file architecture.

## Endpoint Protection

| Endpoint | Anonymous Access |
|---|---|
| `/wp-json/` | Blocked |
| `/wp-json/wp/v2/` | Blocked |
| `/wp-json/wp/v2/users` | Blocked |
| `/wp-json/wp/v2/users/1` | Blocked |
| `/wp-json/oembed/1.0/` | Blocked |
| Registered namespace indexes | Blocked |
| `/?rest_route=/` | Blocked |
| `/?rest_route=/wp/v2/` | Blocked |
| `/wp-json/wp/v2/posts` | Unchanged |
| `/wp-json/wp/v2/pages` | Unchanged |

Authenticated REST API requests remain subject to WordPress authentication and endpoint permission checks.

## Installation

AriffMY REST Security is designed to be installed as a WordPress Must-Use (MU) plugin.

### Step 1: Download

Download `ariffmy-rest-security.php` from this repository.

### Step 2: Create the MU-Plugins Directory

Navigate to your WordPress installation directory and locate:

`wp-content/`

If the `mu-plugins` directory does not exist, create it.

### Step 3: Upload the Plugin

Upload `ariffmy-rest-security.php` directly into:

`wp-content/mu-plugins/`

Your directory structure should look like this:

```
wordpress/
└── wp-content/
    └── mu-plugins/
        └── ariffmy-rest-security.php
```

The PHP file must be placed directly inside the `mu-plugins` directory.

### Step 4: Verify Installation

Log in to the WordPress administration dashboard and navigate to:

**Plugins → Must-Use**

Verify that **REST API Access Protection** appears in the list.

No manual activation is required. WordPress automatically loads the plugin.

### Step 5: Clear Website Cache

Purge all existing website, server, and CDN caches before verifying the installation.

### Step 6: Verify Protection

Open a private or incognito browser window and visit:

`https://yourdomain.com/wp-json/`

The protected endpoint should return HTTP 403 Forbidden.

Verify that WordPress login, administration, Gutenberg, Media Library, and public website functionality continue to operate normally.

## Example Response

Unauthenticated requests to protected REST API endpoints receive an HTTP 403 Forbidden response:

```json
{
  "code": "ariff_access_denied",
  "message": "Access Denied | Protected by ariff.my",
  "data": {
    "status": 403
  }
}
```

## How It Works

The plugin uses the native WordPress `rest_pre_dispatch` filter to inspect incoming REST API requests.

It checks the authenticated user state, normalizes the requested route, and determines whether the route matches a protected REST API index, registered namespace index, or WordPress user endpoint.

All other REST API routes retain their existing WordPress behaviour.

The plugin does not modify WordPress core files.

## Why Must-Use Plugin?

The Must-Use architecture provides several operational advantages:

- Automatically loaded by WordPress.
- No manual activation required.
- Cannot be accidentally deactivated through the standard Plugins dashboard.
- Independent of the active WordPress theme.
- Suitable for centrally managed WordPress environments.
- Simple deployment and maintenance.
- Consistent security hardening across managed installations.

This approach is particularly useful for government portals, corporate websites, institutional websites, and managed WordPress environments.

## Authentication Behaviour

WordPress REST API cookie authentication requires a valid REST nonce.

Opening `/wp-json/` directly in a browser may return HTTP 403 even when the user is logged in to WordPress.

This is expected behaviour when a valid REST nonce is not supplied.

The plugin does not bypass WordPress authentication or nonce verification.

## Cache Compatibility

When using caching solutions such as LiteSpeed Cache, WP Rocket, Nginx FastCGI Cache, or Cloudflare, purge existing cached responses after installing or updating the plugin.

Protected REST API responses should not be served from an inappropriate shared cache.

Ensure that both standard REST API URLs and query-based REST API requests are handled correctly by your caching configuration.

## Security Scope

This plugin is designed as an additional WordPress security hardening layer.

It does not:

- Completely disable the WordPress REST API.
- Prevent every possible method of user enumeration.
- Hide all installed WordPress plugins.
- Automatically resolve CORS misconfigurations.
- Replace a Web Application Firewall (WAF).
- Replace ModSecurity or other server-level protections.
- Guarantee protection against all WordPress vulnerabilities.

For production environments, combine this plugin with regular security updates, strong authentication, appropriate access controls, and server-level security protections.

## Compatibility

The plugin uses native WordPress REST API hooks and does not require external libraries.

Compatibility should be verified with your installed WordPress version, PHP environment, themes, plugins, and caching configuration before production deployment.

Particular attention should be given to:

- WordPress login and administration.
- Gutenberg Block Editor.
- Media Library.
- Public forms.
- Third-party REST API integrations.
- Plugins that depend on REST API discovery.

## Updating

1. Download the latest version of `ariffmy-rest-security.php`.
2. Back up the existing plugin file.
3. Replace the existing file inside `wp-content/mu-plugins/`.
4. Purge all relevant caches.
5. Verify REST API protection and WordPress functionality.

Must-Use plugins are not automatically updated through the standard WordPress plugin update mechanism.

## Troubleshooting

### REST API indexes remain publicly accessible

Purge your website, server, and CDN caches before testing again using a private browser window.

Verify that the plugin appears under **Plugins → Must-Use**.

### WordPress login fails with an unexpected output error

Ensure that the plugin PHP file is saved using UTF-8 without BOM.

There must be no whitespace before the opening PHP tag. Do not include a closing PHP tag at the end of the file.

### REST API returns HTTP 403 while logged in

Direct browser requests may not include a valid WordPress REST nonce.

This is expected behaviour and does not necessarily indicate an authentication issue.

### Plugin conflicts

Temporarily disable the MU-plugin by renaming:

`ariffmy-rest-security.php`

to:

`ariffmy-rest-security.php.disabled`

Verify whether the affected functionality returns to normal.

## Uninstallation

To remove the plugin, delete the following file:

`wp-content/mu-plugins/ariffmy-rest-security.php`

Purge all relevant caches after removal.

The plugin does not create database tables or store configuration data, so no additional database cleanup is required.

## Changelog

### v2.2.0

- Added automatic namespace index protection.
- Improved REST API discovery restrictions.
- Retained public user endpoint protection.
- Preserved authenticated REST API access.

### v2.1.0

- Improved plugin source formatting.
- Addressed deployment-related unexpected output issues.
- Retained selective REST API restrictions.

### v2.0.0

- Introduced selective endpoint protection.
- Removed blanket restrictions on anonymous REST API requests.
- Improved compatibility with WordPress functionality.

### v1.0.0

- Initial implementation.
- Introduced anonymous REST API access restrictions.
- Added custom HTTP 403 responses.

## Security Reporting

If you discover a security vulnerability in this plugin, please report it privately to the maintainer.

Avoid publicly disclosing sensitive vulnerability details before a fix is available.

## Contributing

Contributions, bug reports, feature requests, and security improvements are welcome.

Please ensure that proposed changes preserve WordPress authentication, REST API permissions, and compatibility with existing functionality.

## License

This project is licensed under the GNU General Public License v2.0 or later (GPL-2.0-or-later).

## Author

**ariff.my**

- Website: https://ariff.my/
- GitHub: https://github.com/ariffmy
- Repository: https://github.com/ariffmy/wp-rest-api-access-protection

---

**AriffMY REST Security**

Lightweight by design. Security by principle.

Copyright © 2026 ariff.my.