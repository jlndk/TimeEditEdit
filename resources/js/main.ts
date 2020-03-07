import Lazyloader from './Lazyloader.js';
import UrlConstructor from './UrlConstructor.js';
import Popup from './Popup.js';

const input = document.querySelector('#input') as HTMLInputElement;

const customizeBtn = document.querySelector('#customize-btn') as HTMLButtonElement;
const customizeSection = document.querySelector('#customize-section') as HTMLDivElement;
const plaintextCheckbox = document.querySelector('#plaintext_checkbox') as HTMLInputElement;
const langSelect = document.querySelector('#lang_select') as HTMLSelectElement;

const linkContainer = document.querySelector('.link-container') as HTMLDivElement;
const linkDest = document.querySelector('#link-dest') as HTMLInputElement;
const copyBtn = document.querySelector('#copy-btn') as HTMLButtonElement;

const popupTrigger = document.querySelector('#popup-trigger') as HTMLButtonElement;
const howtoPopup = document.querySelector('#howto-popup');

const lazyLoader = new Lazyloader();
const url = new UrlConstructor();
const popup = new Popup(howtoPopup);
const customize = new Popup(customizeSection);

//Since modules are defer we dont wait for the load event to start lazy loading
lazyLoader.load();

input.addEventListener('keyup', () => {
    url.id = input?.value;
});

plaintextCheckbox.addEventListener('change', () => {
    // url.plaintext = evt.target.checked;
    url.plaintext = plaintextCheckbox.checked;
});

langSelect.addEventListener('change', () => {
    url.lang = langSelect.value;
});

copyBtn.addEventListener('click', () => {
    /* Select the text field */
    linkDest.select();

    /* Copy the text inside the text field */
    document.execCommand('copy');
});

popupTrigger.addEventListener('click', () => {
    popup.open();
});

customizeBtn.addEventListener('click', () => {
    customize.toggle();
});

url.addListener('update', data => {
    if (url.id != '') {
        linkContainer.classList.remove('hidden');
    } else {
        linkContainer.classList.add('hidden');
    }

    linkDest.value = data.url;
});
