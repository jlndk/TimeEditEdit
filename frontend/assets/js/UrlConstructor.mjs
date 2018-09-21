import Dispatcher from './Dispatcher.mjs';

export default class UrlConstructor extends Dispatcher {
    constructor() {
        super();

        //@TODO: Get this dynamicly
        this.domain = "https://timeedit.jlndk.me";

        this.url = "";
        this._id = "";
        this._lang = null;
        this._plaintext = false;
    }

    generate() {

        let params = {};

        if (this.lang) {
            params.lang = this.lang;
        }

        if (this.plaintext) {
            params.plain = true;
        }

        this.url = `${this.domain}/${this.id}${this._encodeQueryParameters(params)}`;
        this.dispatch('update', {
            url: this.url
        });
        return
    }

    set id(val) {
        this._id = val;
        this.generate();
    }

    get id() {
        return this._id;
    }

    set lang(val) {
        this._lang = val;
        this.generate();
    }

    get lang() {
        return this._lang;
    }

    set plaintext(val) {
        this._plaintext = val;
        this.generate();
    }

    get plaintext() {
        return this._plaintext;
    }

    _encodeQueryParameters(data) {
        let str = Object.keys(data).map(function(key) {
            let value = data[key] == true ? "" : data[key];
            let parts = [key, value].map(encodeURIComponent);

            let component = value == "" ? parts.join("") : parts.join("=");

            return component;
        }).join("&");

        if(str != "") {
            str = `?${str}`;
        }

        return str;
    }
}
