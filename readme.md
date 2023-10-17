# Ruttl Bug Tracking WordPress Plugin

![Version](https://img.shields.io/badge/version-1.0.2-green)
![WordPress Version](https://img.shields.io/badge/WordPress-%3E=6.0-blue)
![PHP Version](https://img.shields.io/badge/PHP-%3E=8.0-blue)

**Contributors:** Floris van Leeuwen  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

**This is an unofficial plugin and is in no way related to ruttl.**

Integrate the Ruttl bug tracking script effortlessly into your WordPress website.

## Description

The **Ruttl Bug Tracking** plugin provides a seamless way to embed the Ruttl bug tracking system into your WordPress
platform.

### Features:

- Quick and easy setup of Ruttl Bug Tracking for your WordPress.
- Option to exclude the Bug Tracker for guests (logged-out users).
- Conditional inclusion or exclusion capability with a filter.

## Documentation for Developers

### `flvl_ruttl_bug_tracking/include_ruttl` Filter

This filter enables developers to programmatically decide the inclusion of the Ruttl bug tracking. As a default
behavior, the admin area does not incorporate the Ruttl bug tracking.

```php
add_filter( 'flvl_ruttl_bug_tracking/include_ruttl', function( $include ){
    $include = // your logic here
    
    return $include;
});
```

## Frequently Asked Questions

### How do I set the Project ID?

Go to 'Settings' > 'Ruttl Bug Tracking Settings' and enter your Project ID.

### How can I locate my Project ID?

Sign in to your Ruttl account and proceed to the specific project you wish to integrate with your WordPress site. The
Project ID is the concluding segment of the URL. As an illustration, for the URL `https://web.ruttl.com/project/12345`,
the Project ID would be `12345`.

## Changelog

### 1.0.2

- Security: Escape output of the Project ID.

### 1.0.0

- Initial release.

## License

This plugin is licensed under [GNU General Public License v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).
