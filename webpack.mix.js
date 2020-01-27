const mix = require('laravel-mix');

mix.ts("resources/js/main.js", "public/js")
    .postCss("resources/css/main.css", "public/css", [ require("tailwindcss") ]);
