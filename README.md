# WP Components

This composer library provides php class helpers for typical wordpress extensions.

- abstract Plugin class as plugin starting point
- Component class for plugin component classes
- Extend term with meta values

## Usage

Add the following to your theme or plugins composer.json

```json
{
  ...,
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/palasthotel/wp-components.git"
    }
  ],
  "require": {
    "palasthotel/wp-components": "0.0.1"
  },
  ...
}
```

Then use `composer install` to install the dependencies. Use `composer install --no-cache` if you use dev version `"palasthotel/wp-components": "dev-master"`.