export default class Popup {
    container: Element;

    constructor(containerElem: Element) {
        this.container = containerElem;

        const closeBtn = this.container.querySelector('.btn-close') as HTMLButtonElement;

        this.container.addEventListener('click', evt => {
            if (evt.target == this.container) {
                this.close();
            }
        });

        if (closeBtn !== null) {
            closeBtn.addEventListener('click', () => {
                this.close();
            });
        }
    }

    open(): Popup {
        this.container.classList.remove('hidden');
        return this;
    }

    show(): Popup {
        return this.open();
    }

    close(): Popup {
        this.container.classList.add('hidden');
        return this;
    }

    hide(): Popup {
        return this.close();
    }

    toggle(): Popup {
        this.container.classList.toggle('hidden');
        return this;
    }
}
