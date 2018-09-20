import Lazyloader from './Lazyloader.mjs';
import UrlConstructor from './UrlConstructor.mjs';

const lazyLoader = new Lazyloader();
const url = new UrlConstructor();
const input = document.querySelector("#input");
const plaintextCheckbox = document.querySelector("#plaintext_checkbox");
const langSelect = document.querySelector("#lang_select");
const copyBtn = document.querySelector("#copy-btn");

const linkContainer = document.querySelector(".link-container");
const linkDest = document.querySelector("#link-dest");

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

url.addListener('update', data => {
    if(url.id != "") {
        linkContainer.classList.add('has-text');
    } else {
        linkContainer.classList.remove('has-text');
    }

    linkDest.value = data.url;
});
