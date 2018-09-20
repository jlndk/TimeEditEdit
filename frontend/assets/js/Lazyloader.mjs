export default class Lazyloader {
    load() {
        const lazyloads = document.querySelectorAll('noscript.lazyload');

        // This container is the HTML parser
        const container = document.createElement('div');

        Array.from(lazyloads).forEach(lazyload => {
            const parent = lazyload.parentNode;
            container.innerHTML = lazyload.textContent;
            Array.from(container.children)
            .forEach(n => {
                parent.insertBefore(n, lazyload)
            });
        });
    }
}
