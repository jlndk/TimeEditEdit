import Dispatcher from './Dispatcher.js';

type UrlConstructorParameters = {
    lang: string | null;
    plain: boolean | null;
};

export default class UrlConstructor extends Dispatcher {
    //@TODO: Get this dynamicly
    domain = 'https://timeedit.jlndk.me';
    url = '';
    _id = '';
    _lang: string | null = null;
    _plaintext = false;

    generate(): void {
        const params: UrlConstructorParameters = {
            lang: null,
            plain: null,
        };

        if (this.lang) {
            params.lang = this.lang;
        }

        if (this.plaintext) {
            params.plain = true;
        }

        this.url = `${this.domain}/${this.id}${this._encodeQueryParameters(params)}`;
        this.dispatch('update', {
            url: this.url,
        });
        return;
    }

    set id(val) {
        this._id = val;
        this.generate();
    }

    get id(): string {
        return this._id;
    }

    set lang(val) {
        this._lang = val;
        this.generate();
    }

    get lang(): string | null {
        return this._lang;
    }

    set plaintext(val: boolean) {
        this._plaintext = val;
        this.generate();
    }

    get plaintext(): boolean {
        return this._plaintext;
    }

    _encodeQueryParameters(data: UrlConstructorParameters): string {
        let str = Object.keys(data)
            .map(function(key) {
                const value = data[key] == true ? '' : data[key];
                const parts = [key, value].map(encodeURIComponent);

                const component = value == '' ? parts.join('') : parts.join('=');

                return component;
            })
            .join('&');

        if (str != '') {
            str = `?${str}`;
        }

        return str;
    }
}
