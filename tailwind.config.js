const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
            },
            maxHeight: {
                '70%': '70%',
            },
            backgroundColor: {
                faded: 'rgba(0,0,0,0.8)',
            },
        },
    },
    variants: {},
    plugins: [],
    purge: {
        enabled: true,
        content: ['./resources/views/**/*.blade.php', './resources/js/**/*.ts'],
    },
    future: {
        removeDeprecatedGapUtilities: true,
    },
};
