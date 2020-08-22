export type PopupArgs = {
    container: HTMLElement;
    shouldAnimate?: boolean;
    startOpen?: boolean;
};

export default class Popup {
    container: HTMLElement;
    private _isOpen = false;
    private shouldAnimate: boolean;

    constructor({ container, shouldAnimate = false, startOpen = false }: PopupArgs) {
        this.container = container;
        this.shouldAnimate = shouldAnimate;

        const closeBtn = this.container.querySelector('.btn-close') as HTMLButtonElement;

        this.container.addEventListener('click', evt => {
            if (evt.target === this.container) {
                this.close();
            }
        });

        if (closeBtn !== null) {
            closeBtn.addEventListener('click', () => {
                this.close();
            });
        }

        this.isOpen = startOpen;
    }

    get isOpen(): boolean {
        return this._isOpen;
    }

    set isOpen(val: boolean) {
        this._isOpen = val;
        this.applyClasses();
    }

    open(): Popup {
        this.isOpen = true;
        return this;
    }

    show(): Popup {
        return this.open();
    }

    close(): Popup {
        this.isOpen = false;
        return this;
    }

    hide(): Popup {
        return this.close();
    }

    toggle(): Popup {
        this.isOpen = !this.isOpen;
        return this;
    }

    private applyClasses(): void {
        const closeClasses = this.shouldAnimate ? ['opacity-0', 'pointer-events-none'] : ['hidden'];
        const openClasses = this.shouldAnimate ? ['opacity-100'] : [];
        if (this.isOpen) {
            this.container.classList.remove(...closeClasses);
            this.container.classList.add(...openClasses);
        } else {
            this.container.classList.remove(...openClasses);
            this.container.classList.add(...closeClasses);
        }
    }
}
