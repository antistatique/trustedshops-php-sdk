# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- add Github Actions integration
- add Psalm linter
- add .editorconfig file
- add coveralls phpunit coverage upload
- add coverage on cURL availability tests
- add support for PHP 8.0, 8.1 & 8.2
- add security-checker on Github Actions

### Security
- ugprade phpunit/phpunit 7.x => 8.x
- update phpmd/phpmd (2.7.0 => 2.15.0)
- guzzlehttp/guzzle (6.3.3 => 7.8.1)
- symfony/dotenv (v4.3.4 => v4.4.37)
- guzzlehttp/psr7 (1.6.1 => 2.6.2)
- upgrade friendsofphp/php-cs-fixer (v2.19.3 => v3.4.0)
- upgrade phpunit/phpunit 8.x -> 9.x

### Removed
- remove StyleCI in favor of Github Actions
- drop support for PHP 7.4 and below

## 1.0.0 - 2019-10-08
### Changed
- refactoring of testing strategy
- improve code coverage to ensure stability and sustainability

## 0.0.1-alpha - 2019-09-27
### Added
- allow GET, POST, PUT, PATCH & DELETE operation on the TrustedShops API
- allow Public and Restricted API calls
- expose an option to change the Base URL from Production to Test/QA environment

## 0.0.0 - 2019-09-09
### Added
- under heavy development

[Unreleased]: https://github.com/antistatique/trustedshops-php-sdk/compare/1.0.0...HEAD
