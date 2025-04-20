class PushNotifications {
    constructor() {
        this.publicVapidKey = null;
        this.notificationButton = document.querySelector('.js-notification-toggle');
        this.init();
    }

    async init() {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            this.hideNotificationButton();
            return;
        }

        try {
            // Get VAPID key from server
            const response = await fetch('/wp-json/portfolio/v1/vapid-key');
            this.publicVapidKey = await response.text();

            this.setupEventListeners();
            this.updateButtonState();
        } catch (error) {
            console.error('Failed to initialize push notifications:', error);
            this.hideNotificationButton();
        }
    }

    setupEventListeners() {
        if (this.notificationButton) {
            this.notificationButton.addEventListener('click', () => this.toggleNotifications());
        }
    }

    async toggleNotifications() {
        try {
            const registration = await navigator.serviceWorker.ready;
            const subscription = await registration.pushManager.getSubscription();

            if (subscription) {
                await this.unsubscribeFromPush(subscription);
            } else {
                await this.subscribeUserToPush(registration);
            }

            this.updateButtonState();
        } catch (error) {
            console.error('Error toggling notifications:', error);
        }
    }

    async subscribeUserToPush(registration) {
        try {
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(this.publicVapidKey)
            });

            // Send subscription to server
            await fetch('/wp-json/portfolio/v1/push-subscription', {
                method: 'POST',
                body: JSON.stringify(subscription),
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            return subscription;
        } catch (error) {
            if (Notification.permission === 'denied') {
                console.warn('Permission for notifications was denied');
            } else {
                console.error('Failed to subscribe:', error);
            }
        }
    }

    async unsubscribeFromPush(subscription) {
        try {
            await subscription.unsubscribe();
            // Notify server to remove subscription
            await fetch('/wp-json/portfolio/v1/push-subscription', {
                method: 'DELETE',
                body: JSON.stringify(subscription),
                headers: {
                    'Content-Type': 'application/json',
                }
            });
        } catch (error) {
            console.error('Error unsubscribing:', error);
        }
    }

    async updateButtonState() {
        if (!this.notificationButton) return;

        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();

        this.notificationButton.setAttribute('aria-pressed', Boolean(subscription));
        this.notificationButton.textContent = subscription ? 
            'Disable Notifications' : 
            'Enable Notifications';
    }

    hideNotificationButton() {
        if (this.notificationButton) {
            this.notificationButton.style.display = 'none';
        }
    }

    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }
}

export default PushNotifications;