// SEVA Admin Service Worker — Web Push handler
self.addEventListener('push', function(event) {
    event.waitUntil(
        fetch('/push/latest', { credentials: 'include' })
            .then(function(r) { return r.ok ? r.json() : null; })
            .then(function(data) {
                if (!data) {
                    data = {
                        title: '🛍️ طلب جديد — SEVA',
                        body: 'تم استلام طلب جديد',
                        url: '/admin/orders',
                        tag: 'seva-new-order',
                    };
                }
                return self.registration.showNotification(data.title, {
                    body: data.body,
                    icon: '/seva-logo-transparent.png',
                    badge: '/seva-logo-transparent.png',
                    tag: data.tag || 'seva-order',
                    requireInteraction: true,
                    data: { url: data.url || '/admin/orders' },
                    vibrate: [200, 100, 200, 100, 400],
                });
            })
            .catch(function() {
                return self.registration.showNotification('🛍️ طلب جديد — SEVA', {
                    body: 'تم استلام طلب جديد',
                    icon: '/seva-logo-transparent.png',
                    requireInteraction: true,
                    data: { url: '/admin/orders' },
                });
            })
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    var targetUrl = (event.notification.data && event.notification.data.url) || '/admin/orders';
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(windowClients) {
            for (var i = 0; i < windowClients.length; i++) {
                var client = windowClients[i];
                if (client.url.indexOf('/admin') !== -1 && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
