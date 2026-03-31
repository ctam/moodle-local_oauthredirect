# OAuth Redirect (local_oauthredirect)

A small Moodle local plugin that provides a Moodle-side redirect endpoint to start the OAuth2/OIDC login flow while preserving sesskey and wantsurl parameters.

## Purpose

Many administrators want Moodle's login to automatically redirect to an external OAuth/OIDC provider (Okta, Microsoft, Google). Direct server-level redirects to `/auth/oauth2/login.php?id=...` often fail because `require_sesskey()` expects a session token or the `sesskey` GET parameter. This plugin provides a Moodle-executed endpoint that can safely build the proper redirect URL including `sesskey()` and `wantsurl`.

## Installation

1. Copy the `oauthredirect` folder to `moodle/local/oauthredirect`.
2. Log into Moodle as an admin and visit `Site administration -> Notifications` to install the plugin.
3. Configure settings at `Site administration -> Plugins -> Local plugins -> OAuth Redirect settings`.
   - Choose the default issuer from the dropdown.
   - Toggle `Include sesskey` and `Include wantsurl` as desired.

## Usage

- To use the plugin as a redirect target in your web server (recommended):
  - Point your HTTP server redirect for the login page to:
    - `https://yourmoodle.example.com/local/oauthredirect/redirect.php`
  - That endpoint will read the admin-configured `issuerid`, include `sesskey()` (if enabled) and optional `wantsurl`, then redirect the client to the real OAuth entrypoint `/auth/oauth2/login.php`.

- If you want a public redirect (so external clients can hit it without being logged in), edit `redirect.php` and remove the `require_capability` call. Be cautious: including `sesskey()` for anonymous clients will fail (there won't be a session). Use this endpoint primarily when your server (Nginx/Apache) proxies users to it and sessions exist.

- For testing: you can call `?issuerid=<id>&sesskey=<value>&wantsurl=<path>` to override behavior (for diagnostics only).

## Testing

This plugin includes PHPUnit tests in `tests/`. Run Moodle PHPUnit according to Moodle dev documentation. The test asserts URL construction behaviors and sesskey inclusion.

## Security notes

- `sesskey()` is a CSRF protection token. Do not remove `require_sesskey()` in core or bypass it in production.
- Keep one administrator login method available to avoid lockout.
- If you make the redirect endpoint public, understand that sesskey-based flows require a valid session; for true SSO-only workflows consider using the redirect in combination with a server redirect to a Moodle-generated URL for each user/session.

## License

GPL v3 (same as Moodle).
