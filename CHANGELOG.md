# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

- Code now PSR-12 compliant.

### Added

- Added `Config::getScripts()` method to accommodate refactoring of `Markup::scripts()`.
- Added `Config::getStyles()` method to accommodate refactoring of `Markup::styles()`.
- Can now specify stylesheets for a single page in `contents/site-data.json`.
- Added `contact.css` file to `assets/styles/` folder to demonstrate single page stylesheet.

## [0.2.0] - 22-09-16

### Changed

- Added param to `Markup::pageTitle()` to specify if site title should appended.
- Stripped back the examples in the `contents` and `assets` folders to remove any unrelated dependencies.

## [0.1.0] - 2022-09-15

### Added

- First functioning developmental release (things are likely to change).

[Unreleased]: https://github.com/lmd-code/lmdpages/compare/v0.2.0...HEAD
[0.2.0]: https://github.com/lmd-code/lmdpages/releases/tag/v0.2.0
[0.1.0]: https://github.com/lmd-code/lmdpages/releases/tag/v0.1.0
