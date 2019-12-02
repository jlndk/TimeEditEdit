export default class Popup {
    constructor(containerElem) {
        this.container = containerElem;

        this.closeBtn = this.container.querySelector(".btn-close");

        this.container.addEventListener('click', evt => {
            if (evt.target == this.container) {
                this.close();
            }
        })

        if(this.closeBtn !== null)
        {
            this.closeBtn.addEventListener('click', evt => {
                this.close();
            });
        }
    }

    open() {
        this.container.classList.remove("hidden");
        return this;
    }

    show() {
        return this.open();
    }

    close() {
        this.container.classList.add("hidden");
        return this;
    }

    hide() {
        return this.close();
    }

    toggle() {
        this.container.classList.toggle("hidden");
    }
}
