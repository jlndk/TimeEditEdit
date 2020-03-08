export default class Dispatcher<T extends Function> {
    events = new Map<
        string,
        {
            listeners: T[];
        }
    >();

    addListener(event: string, callback: T): boolean {
        // Check if the callback is not a function
        if (typeof callback !== 'function') {
            console.error(`The listener callback must be a function, the given type is ${typeof callback}`);
            return false;
        }

        // Check if the event is not a string
        if (typeof event !== 'string') {
            console.error(`The event name must be a string, the given type is ${typeof event}`);
            return false;
        }

        // Check if this event not exists
        if (this.events.get(event) === undefined) {
            this.events.set(event, {
                listeners: [],
            });
        }

        this.events.get(event)?.listeners.push(callback);
        return true;
    }

    removeListener(event: string, callback: T): boolean {
        const callbacks = this.events.get(event);

        // Check if this event not exists
        if (callbacks === undefined) {
            console.error(`This event: ${event} does not exist`);
            return false;
        }

        this.events.set(event, {
            listeners: callbacks.listeners.filter(listener => {
                return listener.toString() !== callback.toString();
            }),
        });

        return true;
    }

    dispatch(event: string, details: object): boolean {
        const callbacks = this.events.get(event);

        // Check if this event not exists
        if (callbacks === undefined) {
            console.error(`This event: ${event} does not exist`);
            return false;
        }

        callbacks.listeners.forEach(listener => {
            listener(details);
        });

        return true;
    }
}
