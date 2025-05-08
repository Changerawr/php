# Contributing to Changerawr PHP SDK

Thank you for considering contributing to the Changerawr PHP SDK! This document outlines the process for contributing to this project.

## Code of Conduct

By participating in this project, you agree to abide by its Code of Conduct. Please be respectful and considerate of others.

## How to Contribute

### Reporting Bugs

If you find a bug in the SDK, please create an issue in the GitHub repository with the following information:

1. A clear and descriptive title
2. A detailed description of the bug, including steps to reproduce
3. Expected behavior vs. actual behavior
4. Any relevant code snippets, error messages, or logs
5. Your environment details (PHP version, OS, etc.)

### Suggesting Enhancements

If you have ideas for enhancements or new features, please create an issue in the GitHub repository with the following information:

1. A clear and descriptive title
2. A detailed description of the enhancement or feature
3. Any relevant code snippets or examples of how the feature would work
4. An explanation of why this enhancement would be useful

### Pull Requests

1. Fork the repository
2. Create a new branch from the `main` branch for your changes
3. Make your changes
4. Run tests to ensure your changes don't break existing functionality
5. Submit a pull request

#### Pull Request Guidelines

- Follow the existing code style and conventions
- Include tests for new functionality
- Update documentation as needed
- Keep pull requests focused on a single topic
- Reference any relevant issues in your pull request description

## Development Setup

1. Clone the repository
2. Install dependencies with Composer:
   ```bash
   composer install
   ```
3. Run tests:
   ```bash
   composer test
   ```

## Coding Standards

This project follows PSR-12 coding standards. You can check your code with:

```bash
composer run-script format
```

And analyze it with:

```bash
composer run-script analyse
```

## Testing

Please ensure all tests pass before submitting a pull request:

```bash
composer test
```

If you're adding new functionality, please include tests for it.

## Documentation

If you're adding new features or changing existing ones, please update the relevant documentation.

## License

By contributing to this project, you agree that your contributions will be licensed under the project's MIT License.

Thank you for contributing to Changerawr PHP SDK!