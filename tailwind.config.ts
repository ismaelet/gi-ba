/** @type {import('tailwindcss').Config} */
export default {
	content: ['./resources/**/*.blade.php'],
	theme: {
		// Extended config
		extend: {
			// Custom colors
			colors: {
				// Primary
				primary: 'hsl(var(--color-primary-400-hsl) / <alpha-value>)',
				'primary-100': 'hsl(var(--color-primary-100-hsl) / <alpha-value>)',
				'primary-200': 'hsl(var(--color-primary-200-hsl) / <alpha-value>)',
				'primary-300': 'hsl(var(--color-primary-300-hsl) / <alpha-value>)',
				'primary-400': 'hsl(var(--color-primary-400-hsl) / <alpha-value>)',
				'primary-500': 'hsl(var(--color-primary-500-hsl) / <alpha-value>)',
				'primary-700': 'hsl(var(--color-primary-700-hsl) / <alpha-value>)',

				// Secondary
				secondary: 'hsl(var(--color-secondary-400-hsl) / <alpha-value>)',
				'secondary-400': 'hsl(var(--color-secondary-400-hsl) / <alpha-value>)',

				// Neutral light
				'neutral-light-100': 'hsl(var(--color-neutral-light-100-hsl) / <alpha-value>)',
				'neutral-light-200': 'hsl(var(--color-neutral-light-200-hsl) / <alpha-value>)',
				'neutral-light-300': 'hsl(var(--color-neutral-light-300-hsl) / <alpha-value>)',
				'neutral-light-350': 'hsl(var(--color-neutral-light-350-hsl) / <alpha-value>)',
				'neutral-light-400': 'hsl(var(--color-neutral-light-400-hsl) / <alpha-value>)',
				'neutral-light-700': 'hsl(var(--color-neutral-light-700-hsl) / <alpha-value>)',
				'neutral-light-900': 'hsl(var(--color-neutral-light-900-hsl) / <alpha-value>)',

				// Neutral dark
				'neutral-dark-900': 'hsl(var(--color-neutral-dark-900-hsl) / <alpha-value>)',

				// Neutral theme
				'neutral-100': 'hsl(var(--color-neutral-100-hsl) / <alpha-value>)',
				'neutral-200': 'hsl(var(--color-neutral-200-hsl) / <alpha-value>)',
				'neutral-300': 'hsl(var(--color-neutral-300-hsl) / <alpha-value>)',
				'neutral-350': 'hsl(var(--color-neutral-350-hsl) / <alpha-value>)',
				'neutral-400': 'hsl(var(--color-neutral-400-hsl) / <alpha-value>)',
				'neutral-700': 'hsl(var(--color-neutral-700-hsl) / <alpha-value>)',
				'neutral-800': 'hsl(var(--color-neutral-800-hsl) / <alpha-value>)',
				'neutral-900': 'hsl(var(--color-neutral-900-hsl) / <alpha-value>)',

				// Background
				background: 'var(--color-background)',

				// Surfaces
				'surface-400': 'hsl(var(--color-surface-400-hsl) / <alpha-value>)',
				'surface-500': 'hsl(var(--color-surface-500-hsl) / <alpha-value>)',
				'surface-700': 'hsl(var(--color-surface-700-hsl) / <alpha-value>)',

				// Borders
				border: 'var(--border-color)',

				// Utility colors
				success: 'hsl(var(--color-success-400-hsl) / <alpha-value>)',
				error: 'hsl(var(--color-error-400-hsl) / <alpha-value>)',
			},
			// Custom borders
			borderWidth: {
				DEFAULT: 'var(--border-width)',
			},
			// Custom border radius
			borderRadius: {
				xs: 'var(--border-radius-100)',
				sm: 'var(--border-radius-200)',
				DEFAULT: 'var(--border-radius-400)',
				lg: 'var(--border-radius-700)',
				xl: 'var(--border-radius-900)',
				full: 'var(--border-radius-1000)',
			},
			// Custom font families
			fontFamily: {
				sans: 'var(--font-family-paragraph)',
				heading: 'var(--font-family-heading)',
				serif: 'var(--font-family-serif)',
			},
			fontWeight: {
				thin: 'var(--font-weight-thin)',
				hairline: 'var(--font-weight-hairline)',
				extralight: 'var(--font-weight-extralight)',
				light: 'var(--font-weight-light)',
				normal: 'var(--font-weight-normal)',
				medium: 'var(--font-weight-medium)',
				semibold: 'var(--font-weight-semibold)',
				bold: 'var(--font-weight-bold)',
				'extra-bold': 'var(--font-weight-extra-bold)',
				black: 'var(--font-weight-black)',
			},
			// New spacing
			spacing: {
				'content-padding': 'var(--content-padding)',
			},
			// Default border color
			borderColor: {
				DEFAULT: 'var(--border-color)',
			},
			// New box shadows
			boxShadow: {
				DEFAULT: 'var(--shadow-400)',
				lg: 'var(--shadow-700)',
			},
			// New font sizes
			fontSize: {
				// Headings
				'main-heading': [
					'var(--font-size-900)',
					{
						fontWeight: 'var(--font-weight-bold)',
						letterSpacing: '0.03em',
						lineHeight: '1.1',
					},
				],
				'section-heading': [
					'var(--font-size-800)',
					{
						fontWeight: 'var(--font-weight-bold)',
						letterSpacing: '0.03em',
						lineHeight: '1.2',
					},
				],
				'component-heading': [
					'var(--font-size-700)',
					{
						fontWeight: 'var(--font-weight-semibold)',
						letterSpacing: '0.03em',
						lineHeight: '1.2',
					},
				],

				// Subheadings
				'main-subheading': [
					'var(--font-size-700)',
					{
						fontWeight: 'var(--font-weight-semibold)',
						letterSpacing: '0.05em',
						lineHeight: '1.2',
					},
				],
				'section-subheading': [
					'var(--font-size-700)',
					{
						fontWeight: 'var(--font-weight-semibold)',
						letterSpacing: '0.05em',
						lineHeight: '1.2',
					},
				],
				'component-subheading': [
					'var(--font-size-700)',
					{
						fontWeight: 'var(--font-weight-semibold)',
						letterSpacing: '0.05em',
						lineHeight: '1.2',
					},
				],

				// Paragraphs
				'big-paragraph': [
					'1.25rem',
					{
						lineHeight: '1.75',
					},
				],
				paragraph: [
					'var(--font-size-400)',
					{
						lineHeight: '1.75',
					},
				],
				'small-paragraph': ['var(--font-size-300)', {}],
			},
		},
	},
	plugins: [],
}
