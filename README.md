# WPMU ACF Fatal Error When Used Too Early

An MU plugin that makes early Advanced Custom Fields (ACF) access fail loudly.

When ACF triggers its `acf/get_invalid_field_value` hook before WordPress has run the `init` action, this plugin inspects the call stack. If the invalid value originated from `get_field()`, it throws an exception containing the function name, file, and line number.

## Requirements

- WordPress with must-use plugins enabled.
- Advanced Custom Fields (ACF).

## Installation

1. Place `wpmu-acf-fatal-error-when-used-too-early.php` in the WordPress `wp-content/mu-plugins/` directory.
2. Reproduce the request that accesses an ACF field too early.
3. Use the exception's file and line number to move the `get_field()` call to `init` or a later WordPress hook.

MU plugins load automatically and cannot be activated or deactivated from the WordPress administration interface.

## Scope

The plugin only raises an exception when all of the following are true:

- WordPress has not yet completed the `init` action.
- ACF emits `acf/get_invalid_field_value`.
- The call stack contains `get_field()`.

It does not affect valid ACF field access or field access that happens after `init`.

## Removing the Plugin

Remove `wpmu-acf-fatal-error-when-used-too-early.php` from `wp-content/mu-plugins/` to stop this diagnostic behavior.