# Changelog

This is currently a DEVELOPMENT CHANGELOG and things are likely to change before the first proper release.

## [0.3.0] - 2022-10-31

### Changed

- Moved location of `site-data.json` file to `contents/data/site-data.json`.
- Can now specify stylesheets for a single page in `contents/data/site-data.json`.

### Added

- Added `Config::getScripts()` method to accommodate refactoring of `Markup::scripts()`.
- Added `Config::getStyles()` method to accommodate refactoring of `Markup::styles()`.
- Added `assets/styles/contact.css` file to demonstrate single page stylesheet.

## [0.2.0] - 2022-09-16

### Changed

- Added param to `Markup::pageTitle()` to specify if site title should appended.
- Stripped back the examples in the `contents` and `assets` folders to remove any unrelated dependencies.

## [0.1.0] - 2022-09-15

*First functioning developmental release (things are likely to change).*

[0.3.0]: https://github.com/lmd-code/lmdpages/releases/tag/v0.3.0
[0.2.0]: https://github.com/lmd-code/lmdpages/releases/tag/v0.2.0
[0.1.0]: https://github.com/lmd-code/lmdpages/releases/tag/v0.1.0
