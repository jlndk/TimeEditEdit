<!DOCTYPE html>
<html lang="en" dir="ltr" class="h-full">
    <head>
        <meta charset="utf-8">
        <title>TimeEditEdit</title>
        <meta name="Description" content="A proxy for intercepting the ugly TimeEdit Schedule and making it readable">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Lazyload assets in browsers that has javascript, and revert back to normal loading when they dont -->
        <!-- Link: https://dassur.ma/things/lazyloading/ -->
        <noscript class="lazyload">
            <link href="/css/main.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&display=swap" rel="stylesheet">
        </noscript>
    </head>

    <body class="font-sans bg-itu h-full flex items-center justify-center">

        <aside id="howto-popup" class="hidden">
            <article>
                <button class="btn-close">&times;</button>
                <div>
                    <h1>How do I use this site?</h1>
                    <p>To use this service you need to obtain the id of a valid schedule on TimeEdit.</p>
                    <p>This can easily be done though the TimeEdit website, once you have configured/have access to your schedule.</p>
                    <p>On the page for your chosen schedule you need to obtain the subscribtion link by pressing the <img src="/img/instructions-subscribe.png" alt="Subscribtion Button"> button and make a copy of the link that is now shown.</p>
                    <p>The link should be in the following format: https://cloud.timeedit.net/itu/web/public/<b>abc123abc123</b>.ics</p>
                    <p>The id that should be provided to this service is the text between <i>public/</i> and <i>.ics</i> (<b>abc123abc123</b> in the example above)</p>
                    <p>Now that the TimeEdit id is obtain, you simply need to paste the link in the textbox on this site. You will now immedially be presented with a new link that can be pasted directly into your calendar program of choise.</p>

                    <b>Enjoy your new shiny schedule!</b>
                    <br><br>


                    <h1>How does it work?</h1>
                    <p>TimeEditEdit acts as a proxy (middleman) between your calendar program (such as Google calendar and outlook) and TimeEdit.</p>
                    <p>This means that when your calendar program tries to fetch new updates to the schedule, it asks our service instead of TimeEdit directly.</p>
                    <p>Our service then downloads the schedule from TimeEdit, performs the transformations and proper formatting and then sends it to your calendar program.</p>

                    <p>In more technical terms the TimeEdit schedule is distribued as an ICS file (which is the de-facto standard file type for distributing and sharing calendar events).</p>
                    <p>By parsing this file we can extract the important information, perform sensible modifications (such as translations) and afterwards generate a new ICS file that is send to your calendar program.</p>
                </div>
            </article>
        </aside>

        <main class="flex flex-col bg-white text-center shadow-lg rounded-lg px-4 py-4 max-w-lg">
            <h1 class="text-center text-4xl font-light">TimeEditEdit</h1>

            <div class="link-container hidden">
                <label for="input">Here is your new improved link:</label>
                <div class="inner">
                    <input id="link-dest" type="text" class="bg-gray-200 px-3 py-3 rounded-lg" readonly>
                    <button id="copy-btn" class="btn-copy">&#9986;</button>
                </div>
            </div>

            <label for="input">Enter TimeEdit Id Here:</label>
            <input class="bg-gray-200 px-3 py-3 rounded-lg placeholder-gray-700 text-gray-800" type="text" id="input" placeholder="Enter your timeedit id here" aria-label="Enter your timeedit id here">
            <a class="text-left text-sm pl-3" id="popup-trigger" href="#">How do I use this site?</a>

            <h2 class="font-bold">Options:</h2>

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

            <footer>
                <p class="text-sm">This project is open source!<br>Check us out on <a target="_blank" rel="noopener" href="https://github.com/jlndk/TimeEditEdit">https://github.com/jlndk/TimeEditEdit</a></p>
                <br>
                <p class="text-xs text-gray-700">Created by Jonas Lindenskov Nielsen (<a target="_blank" rel="noopener" href="https://jlndk.me">https://jlndk.me</a>).<br> This project is neither assosiated with TimeEdit nor The IT University of Copenhagen.<br> This project is licensed under these <a target="_blank" rel="noopener" href="https://github.com/jlndk/TimeEditEdit/blob/master/LICENSE.md">Terms and conditions</a></p>
            </footer>
        </main>
        <script src="js/main.js"></script>
    </body>

</html>