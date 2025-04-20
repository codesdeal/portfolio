# Contributing to Portfolio Theme

Thank you for considering contributing to our WordPress theme! This document outlines the standards and processes we follow.

## Code Standards

### PHP
- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use PHP 7.4+ features when appropriate
- Document classes and functions using PHPDoc blocks
- Maintain proper sanitization and escaping

### JavaScript
- Use ES6+ features
- Follow ESLint configuration
- Write unit tests for new functionality
- Document complex logic with JSDoc comments

### CSS/SCSS
- Follow BEM naming convention
- Use modern CSS features with appropriate fallbacks
- Maintain WCAG 2.1 AA compliance
- Follow Stylelint configuration

## Development Process

1. Create a feature branch from `main`:
```bash
git checkout -b feature/your-feature-name
```

2. Write tests first (TDD approach)
3. Implement your changes
4. Ensure all tests pass:
```bash
composer test
npm test
```

5. Run linting:
```bash
npm run lint
```

6. Update documentation if needed

## Pull Request Process

1. Update the README.md with details of changes if needed
2. Add tests for new functionality
3. Ensure the test suite passes
4. Update the version number following [SemVer](https://semver.org/)
5. Request review from maintainers

## Commit Messages

Follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

- feat: New feature
- fix: Bug fix
- docs: Documentation changes
- style: Code style changes
- refactor: Code refactoring
- test: Test updates
- chore: Maintenance tasks

## Testing Guidelines

### PHP Tests
- Write unit tests for all new functions
- Mock WordPress functions using WP_Mock
- Test edge cases and error conditions
- Maintain 80%+ code coverage

### JavaScript Tests
- Use Jest and Testing Library
- Test component functionality
- Test user interactions
- Write integration tests for complex features

## Accessibility Requirements

- Maintain WCAG 2.1 AA compliance
- Test with screen readers
- Ensure keyboard navigation
- Provide proper ARIA attributes
- Test color contrast

## Performance Considerations

- Optimize images
- Minimize JavaScript bundles
- Use lazy loading where appropriate
- Follow WordPress performance best practices
- Test with Lighthouse before submitting PR

## Questions or Problems?

Open an issue in the repository or contact the maintainers directly.