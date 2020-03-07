export default class Lazyloader {
    load(): void {
        const lazyloads = document.querySelectorAll<HTMLScriptElement>('noscript.lazyload');

        // This container is the HTML parser
        const container = document.createElement('div') as HTMLDivElement;

        Array.from(lazyloads).forEach(lazyload => {
            const parent = lazyload.parentNode;
            if (parent == null) {
                throw new Error('lazyload container has no parent.');
            }
            container.innerHTML = lazyload.textContent as string;
            Array.from(container.children).forEach(n => {
                parent.insertBefore(n, lazyload);
            });
        });
    }
}
