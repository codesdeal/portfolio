# Portfolio WordPress Theme

A modern WordPress theme with a focus on performance, accessibility, and developer experience.

## Features

- Modern development workflow with Gulp
- SCSS preprocessing with modern CSS features
- JavaScript bundling with Webpack
- Image optimization with WebP support
- Critical CSS generation
- Service Worker for offline support
- Comprehensive testing suite (PHP & JavaScript)
- Accessibility-first approach
- SEO optimizations with schema markup
- Developer-friendly documentation

## Requirements

- PHP 7.4 or higher
- WordPress 5.8 or higher
- Node.js 16 or higher
- Composer

## Installation

1. Clone this repository to your WordPress themes directory:
```bash
cd wp-content/themes
git clone [repository-url] portfolio
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Build assets:
```bash
npm run build
```

## Development

### Available Commands

- `npm start` - Start development server with live reload
- `npm run build` - Build assets for production
- `npm run bundle` - Create distributable theme package
- `npm run lint` - Run JavaScript and SCSS linting
- `npm test` - Run JavaScript tests
- `composer test` - Run PHP tests

### Development Workflow

1. Start the development server:
```bash
npm start
```

2. Make your changes in the `src` directory
3. Run tests and linting before committing:
```bash
npm test && npm run lint
```

### File Structure

- `src/` - Source files (SCSS, JavaScript, images)
- `dist/` - Compiled assets
- `inc/` - Theme PHP includes
- `lib/` - Core theme functionality
- `template-parts/` - Reusable template parts
- `tests/` - Test files

## Testing

### PHP Tests

```bash
composer test
```

### JavaScript Tests

```bash
npm test
# or with watch mode:
npm run test:watch
```

## Performance Optimizations

- Critical CSS generation
- Image optimization with WebP support
- Service Worker for offline functionality
- Lazy loading for images
- Resource hints for external resources
- Browser caching headers

## Accessibility Features

- ARIA landmarks and labels
- Keyboard navigation support
- Skip links
- Color contrast compliance
- Screen reader text
- Focus management

## Contributing

1. Fork the repository
2. Create your feature branch
3. Run tests and ensure they pass
4. Submit a pull request

See [CONTRIBUTING.md](CONTRIBUTING.md) for detailed guidelines.

## License

ISC License - See LICENSE file for details