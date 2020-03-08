const mix = require("laravel-mix");

mix.ts("resources/js/main.ts", "public/js").postCss(
    "resources/css/main.css",
    "public/css",
    [require("tailwindcss")]
);
