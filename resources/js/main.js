import Lazyloader from './Lazyloader.js';
import UrlConstructor from './UrlConstructor.js';
import Popup from './Popup.js';

const input = document.querySelector("#input");

const customizeBtn = document.querySelector("#customize-btn");
const customizeSection = document.querySelector("#customize-section");
const plaintextCheckbox = document.querySelector("#plaintext_checkbox");
const langSelect = document.querySelector("#lang_select");

const linkContainer = document.querySelector(".link-container");
const linkDest = document.querySelector("#link-dest");
const copyBtn = document.querySelector("#copy-btn");

const popupTrigger = document.querySelector("#popup-trigger");
const howtoPopup = document.querySelector("#howto-popup");

const lazyLoader = new Lazyloader();
const url = new UrlConstructor();
const popup = new Popup(howtoPopup);
const customize = new Popup(customizeSection);

//Since modules are defer we dont wait for the load event to start lazy loading
lazyLoader.load();

input.addEventListener('keyup', evt => {
    url.id = input.value;
});

plaintextCheckbox.addEventListener('change', evt => {
    url.plaintext = evt.target.checked;
});

langSelect.addEventListener('change', evt => {
    url.lang = evt.target.value;
});

copyBtn.addEventListener('click', evt => {
    /* Select the text field */
    linkDest.select();

    /* Copy the text inside the text field */
    document.execCommand("copy");
});

popupTrigger.addEventListener('click', evt => {
    popup.open();
});

customizeBtn.addEventListener('click', evt => {
    customize.toggle();
})

url.addListener('update', data => {
    if(url.id != "") {
        linkContainer.classList.remove('hidden');
    } else {
        linkContainer.classList.add('hidden');
    }

    linkDest.value = data.url;
});
