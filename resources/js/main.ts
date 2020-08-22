import Lazyloader from './Lazyloader';
import UrlConstructor from './UrlConstructor';
import Popup from './Popup';

// Reference DOM elements (using casts since we know they all exist)
const input = document.querySelector('#input') as HTMLInputElement;

const customizeBtn = document.querySelector('#customize-btn') as HTMLButtonElement;
const customizeSection = document.querySelector('#customize-section') as HTMLDivElement;
const plaintextCheckbox = document.querySelector('#plaintext_checkbox') as HTMLInputElement;
const langSelect = document.querySelector('#lang_select') as HTMLSelectElement;

const linkContainer = document.querySelector('.link-container') as HTMLDivElement;
const linkDest = document.querySelector('#link-dest') as HTMLInputElement;
const copyBtn = document.querySelector('#copy-btn') as HTMLButtonElement;

const popupTrigger = document.querySelector('#popup-trigger') as HTMLButtonElement;
const howtoPopup = document.querySelector('#howto-popup') as HTMLDivElement;

// Construct "Logic" classes
const lazyLoader = new Lazyloader();
const url = new UrlConstructor();
const popup = new Popup({ container: howtoPopup, shouldAnimate: true });
const customize = new Popup({ container: customizeSection });

//Since modules are defer we dont wait for the load event to start lazy loading
lazyLoader.load();

input.addEventListener('keyup', () => {
    const regex = /https?:\/\/cloud.timeedit.net\/itu\/web\/public\/(.+)\.ics/;
    const value = input?.value;
    // Atempt to extract id from full timeedit url, or simply use the input as id otherwise
    url.id = regex.exec(value)?.[1] ?? value;
});

// Set settings in URL generator when UI change
plaintextCheckbox.addEventListener('change', () => (url.plaintext = plaintextCheckbox.checked));
langSelect.addEventListener('change', () => (url.lang = langSelect.value));

// Copy content of result input when the "Copy" button is clicked
copyBtn.addEventListener('click', () => {
    /* Select the text field */
    linkDest.select();

    /* Copy the text inside the text field */
    document.execCommand('copy');
});

// Trigger popups
popupTrigger.addEventListener('click', () => popup.open());
customizeBtn.addEventListener('click', () => customize.toggle());
url.addListener('update', (data: { url: string }) => {
    // Show or hide the result input based on the given id
    if (url.id == '') {
        linkContainer.classList.add('hidden');
    } else {
        linkContainer.classList.remove('hidden');
    }

    linkDest.value = data.url;
});
