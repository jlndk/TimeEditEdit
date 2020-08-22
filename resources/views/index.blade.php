<!DOCTYPE html>
<html lang="en" dir="ltr" class="h-full">

<head>
    <meta charset="utf-8">
    <title>TimeEditEdit</title>
    <meta name="Description" content="A proxy for intercepting the ugly TimeEdit Schedule and making it readable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/main.css" rel="stylesheet">

    <!-- Lazyload assets in browsers that has javascript, and revert back to normal loading when they dont -->
    <!-- Link: https://dassur.ma/things/lazyloading/ -->
    <noscript class="lazyload">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap" rel="stylesheet">
    </noscript>
</head>

<body class="font-sans bg-itu h-full flex flex-col items-center justify-center px-3">

    <aside id="howto-popup" class="fixed bg-faded top-0 left-0 right-0 bottom-0 px-3 z-50 flex flex-col justify-center items-center transition-opacity duration-100 opacity-0 pointer-events-none">
        <article class="bg-white relative w-full max-w-xl max-h-70% overflow-y-hidden rounded-lg shadow-lg">
            <button class="btn-close absolute top-0 right-0 text-3xl font-bold mr-5 mt-1 leading-none">&times;</button>
            <div class="overflow-y-auto max-h-full px-6 py-4">
                <h2 class="font-bold text-lg">How do you use this site?</h2>
                <p>To use this service you need to obtain the subscription link of a valid schedule on TimeEdit.</p>
                <p>This can be done though the TimeEdit website, once you have access to your schedule.</p>
                <p>On the page for your chosen schedule you need to obtain the subscribtion link by pressing the <img src="/img/instructions-subscribe.png" alt="Subscribtion Button"> button and make a copy of the link that is now shown.</p>
                <p>This link must simply be pasted information the textbox on this site. You will now immedially be presented with a new link that can be pasted directly into your calendar program of choise.</p>

                <b>Enjoy your new shiny schedule!</b>
                <br><br>


                <h2 class="font-bold text-lg">How does it work?</h2>
                <p>TimeEditEdit acts as a proxy (middleman) between your calendar program (such as Google Calendar or outlook) and TimeEdit.</p>
                <p>This means that when your calendar program tries to fetch new updates to the schedule, it asks our service instead of TimeEdit directly.</p>
                <p>When this happens, the schedule is downloaded from TimeEdit, transformations and proper formatting are performed and afterwards sent to your calendar program.</p>

                <p>In more technical terms the TimeEdit schedule is distribued as an ICS file (which is the de-facto standard file type for distributing and sharing calendar events).</p>
                <p>By parsing this file we can extract the important information, perform sensible modifications (such as translations) and afterwards generate a new ICS file that is sent to your calendar program.</p>
            </div>
        </article>
    </aside>

    <main class="flex flex-col bg-white text-center shadow-lg rounded-lg px-4 py-4 mb-4 max-w-lg">

        <h1 class="text-center text-4xl font-light leading-tight my-2">TimeEditEdit</h1>

        <div class="flex bg-gray-200 rounded-lg mb-4">
            <input class="bg-transparent w-full px-3 py-3 placeholder-gray-700 text-gray-800" type="text" id="input" placeholder="Paste TimeEdit Link or Id Here" aria-label="Paste time edit link or id here">
            <button id="customize-btn" class="flex flex-col items-center justify-center px-1 border-l border-gray-400" style="width: 70px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-6 text-gray-900 fill-current">
                    <path d="M6.47 9.8A5 5 0 0 1 .2 3.22l3.95 3.95 2.82-2.83L3.03.41a5 5 0 0 1 6.4 6.68l10 10-2.83 2.83L6.47 9.8z" /></svg>
                <span class="block text-xs text-gray-700">Customize</span>
            </button>
        </div>
        <a class="flex justify-center text-sm text-gray-800 mb-4 font-bold" id="popup-trigger" href="#">
            <div class="w-5 mx-1 text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current">
                    <path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm2-13c0 .28-.21.8-.42 1L10 9.58c-.57.58-1 1.6-1 2.42v1h2v-1c0-.29.21-.8.42-1L13 9.42c.57-.58 1-1.6 1-2.42a4 4 0 1 0-8 0h2a2 2 0 1 1 4 0zm-3 8v2h2v-2H9z" /></svg>
            </div>
            How do i use this site?
        </a>
        
        <div class="link-container hidden py-4 mb-2 -mx-4 px-4 bg-gray-400">
            <label class="font-bold text-gray-800" for="input">Here is your new improved link:</label>
            <div class="flex bg-gray-200 border border-gray-400 rounded-lg">
                <input id="link-dest" type="text" class="bg-transparent px-3 py-3 w-full text-center placeholder-gray-700 text-gray-800" readonly>
                <button id="copy-btn" class="flex flex-col items-center justify-center px-1 border-l border-gray-400" style="width: 70px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-6 text-gray-900 fill-current">
                        <path d="M6 6V2c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-4v4a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8c0-1.1.9-2 2-2h4zm2 0h4a2 2 0 0 1 2 2v4h4V2H8v4zM2 8v10h10V8H2z" /></svg>
                    <span class="block text-xs text-gray-700">Copy</span>
                </button>
            </div>
        </div>

        <section id="customize-section" class="flex flex-col mb-4 hidden">
            <h2 class="font-bold">Customization:</h2>

            <label for="plaintext_checkbox" class="checkbox-container">
                Plaintext-mode:
                <input id="plaintext_checkbox" type="checkbox">
                <span class="checkmark"></span>
            </label>

            <label for="lang_select">Language:</label>
            <select id="lang_select" class="bg-gray-200 px-4 py-2 rounded-lg">
                <option value="da">Danish</option>
                <option value="en">English</option>
            </select>
        </section>

        <footer>
            <p class="text-xs text-gray-700">Created by Jonas Lindenskov Nielsen (<a class="underline" target="_blank" rel="noopener" href="https://lindenskov.dev">https://lindenskov.dev</a>).<br> This project is neither assosiated with TimeEdit nor The IT University of Copenhagen.<br> This project is licensed under these <a class="underline" target="_blank" rel="noopener" href="https://github.com/jlndk/TimeEditEdit/blob/master/LICENSE.md">Terms and conditions</a></p>
        </footer>
    </main>

    <div class="bg-white flex flex-col text-center px-4 py-4 w-full max-w-lg rounded-lg shadow-lg">
        <p class="text-sm"><b>Did you know</b> this project is open source!<br>Check us out on <a class="underline" target="_blank" rel="noopener" href="https://github.com/jlndk/TimeEditEdit">https://github.com/jlndk/TimeEditEdit</a></p>
    </div>

    <script src="js/main.js"></script>
</body>

</html>