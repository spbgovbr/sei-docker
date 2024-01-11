# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## [0.2.0](https://github.com/tuupola/base32/compare/0.1.1...0.2.0) - 2019-05-05

### Added
- Implicit `decodeInteger()` and `encodeInteger()` methods ([#4](https://github.com/tuupola/base32/pull/4)).
- Character set validation for configuration ([#6](https://github.com/tuupola/base32/pull/6)).
- Character set validation for incoming data ([#7](https://github.com/tuupola/base32/pull/7)).

### Removed
- The unused and undocumented `$options` parameter from static proxy methods ([#5](https://github.com/tuupola/base32/pull/5)).

## [0.1.1](https://github.com/tuupola/base32/compare/0.1.0...0.1.1) - 2018-04-13

### Fixed
- Removed `robinvdvleuten/ulid` and `lewiscowles/ulid` from composer.json. These were accidentally included.

## 0.1.0 - 2016-06-26

Initial realese.
