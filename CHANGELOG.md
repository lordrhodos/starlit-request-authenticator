# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2019-03-19
### Added
- Request agnostic support, with built in support for the following libraries:
 - PSR-7
 - Symfony requests (symfony/http-foundation)
 - Guzzle5 requests (Guzzl6 implements PSR-7 interfaces)
- Supports passing in a custom HmacDataTransformerInterface implementation if the format of the data string needs to be customized

## [1.0.1] - 2019-02-26
### Changed
- downgrade symfony/http-foundation package 

## [1.0.0] - 2019-01-25
### Added
- Initial release
