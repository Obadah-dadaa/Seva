// SEVA Service Worker — Web Push handler (admin + customer)
self.addEventListener('push', function(event) {
    // Try to parse payload first (future encrypted notifications)
    var payload = null;
    try { payload = event.data ? event.data.json() : null; } catch(e) {}

    if (payload && payload.title) {
        event.waitUntil(
            self.registration.showNotification(payload.title, {
                body: payload.body || '',
                icon: '/seva-logo-transparent.png',
                badge: '/seva-logo-transparent.png',
                tag: payload.tag || 'seva-notif',
                requireInteraction: true,
                data: { url: payload.url || '/' },
                vibrate: [200, 100, 200, 100, 400],
            })
        );
        return;
    }

    // Ping-style push — try user-specific endpoint first, then admin fallback
    event.waitUntil(
        fetch('/push/user-latest', { credentials: 'include' })
            .then(function(r) { return r.ok ? r.json() : null; })
            .then(function(data) {
                if (data && data.title) {
                    // Customer notification
                    return self.registration.showNotification(data.title, {
                        body: data.body || '',
                        icon: '/seva-logo-transparent.png',
                        badge: '/seva-logo-transparent.png',
                        tag: data.tag || 'seva-order-update',
                        requireInteraction: true,
                        data: { url: data.url || '/my-orders' },
                        vibrate: [200, 100, 200, 100, 400],
                    });
                }
                // Null means admin or unauthenticated — fall through to admin endpoint
                return fetch('/push/latest', { credentials: 'include' })
                    .then(function(r) { return r.ok ? r.json() : null; })
                    .then(function(adminData) {
                        if (!adminData) adminData = { title: '🛍️ طلب جديد — SEVA', body: 'تم استلام طلب جديد', url: '/admin/orders', tag: 'seva-new-order' };
                        return self.registration.showNotification(adminData.title, {
                            body: adminData.body,
                            icon: '/seva-logo-transparent.png',
                            badge: '/seva-logo-transparent.png',
                            tag: adminData.tag || 'seva-order',
                            requireInteraction: true,
                            data: { url: adminData.url || '/admin/orders' },
                            vibrate: [200, 100, 200, 100, 400],
                        });
                    });
            })
            .catch(function() {
                return self.registration.showNotification('🛍️ SEVA', {
                    body: 'لديك إشعار جديد',
                    icon: '/seva-logo-transparent.png',
                    requireInteraction: true,
                    data: { url: '/' },
                });
            })
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    var targetUrl = (event.notification.data && event.notification.data.url) || '/';
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(windowClients) {
            for (var i = 0; i < windowClients.length; i++) {
                var client = windowClients[i];
                if ('focus' in client) {
                    client.navigate(targetUrl);
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
