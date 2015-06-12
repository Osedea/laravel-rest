# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [0.2.0] - 2015-06-12
### Changed
- Update README file with last changes

### Added
- Created the changelog file based on [Keep a CHANGELOG](http://keepachangelog.com/)
- `CommandModel` trait with fields and methods required by the commands

### Breaking changes
- The `TranslatorService::getClassFromResource` method now uses `api.models_namespace` config parameter instead of
  `api.app_namespace` (which is still used in the `TranslatorService` class).
- Models need to use the `CommandModel` trait and you have to remove the code in the model that's now in the trait.
