import tailwindForm from "@tailwindcss/forms"

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
        "./vendor/symfony/twig-bridge/Resources/views/Form/*.html.twig"
    ],
    theme: {
        extend: {
            colors: {
                'theme-form': '#f4f1f1',
                'theme-form-header': '#e0e0e3',
                'theme-accent-1': '#F1FAEE',
                'theme-accent-2': '#A8DADC',
                'theme-accent-3': '#45789D',
                'theme-blue': '#1D3557',
                'theme-red': '#E63946',
            },
            fontFamily: {
                headingText: ['"Baloo 2"', "Regular"],
                accentText: ['"Adamina"', "Regular"],
                bodyText: ['"Lato"', "Regular"],
            },
        },
    },
    plugins: [tailwindForm],
}
