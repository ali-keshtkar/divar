module.exports = {
    content: [
        "./Modules/**/Resources/views/*.blade.php",
        "./Modules/**/Resources/views/**/*.blade.php",
        "./Modules/**/Resources/views/**/**/*.blade.php"
    ],
    theme: {
        extend: {
            colors: {
                "primary": "#a62626",
                "primary-light": "#be3737"
            }
        },
    },
    plugins: [],
}
