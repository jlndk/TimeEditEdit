export default class Popup {
    constructor(containerElem) {
        this.container = containerElem;

        this.closeBtn = this.container.querySelector(".btn-close");

        this.container.addEventListener('click', evt => {
            if (evt.target == this.container) {
                this.close();
            }
        })

        this.closeBtn.addEventListener('click', evt => {
            this.close();
        });
    }

    open() {
        this.container.classList.add("open");
        return this;
    }

    show() {
        return this.open();
    }

    close() {
        this.container.classList.remove("open");
        return this;
    }

    hide() {
        return this.close();
    }

    toggle() {
        this.container.classList.toggle("open");
    }
}
