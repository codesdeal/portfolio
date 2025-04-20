<?php
/**
 * Template Name: Offline Page
 * Description: A template for displaying offline content when the user has no internet connection
 */

get_header(); ?>

<div class="c-offline">
    <div class="c-offline__content">
        <h1 class="c-offline__title">
            <?php esc_html_e('You\'re Offline', '_themename'); ?>
        </h1>
        
        <p class="c-offline__message">
            <?php esc_html_e('It looks like you\'ve lost your internet connection. Please check your network and try again.', '_themename'); ?>
        </p>
        
        <div class="c-offline__status">
            <div id="connection-status"></div>
            <div id="last-updated"></div>
        </div>

        <div class="c-offline__actions">
            <button class="c-button c-button--primary" onclick="window.location.reload()">
                <?php esc_html_e('Retry Connection', '_themename'); ?>
            </button>
            
            <button class="c-button c-button--secondary" id="view-offline-content">
                <?php esc_html_e('View Offline Content', '_themename'); ?>
            </button>
        </div>

        <div class="c-offline__cached-content" style="display: none;">
            <h2><?php esc_html_e('Available Offline Content', '_themename'); ?></h2>
            <div id="cached-pages"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const connectionStatus = document.getElementById('connection-status');
    const lastUpdated = document.getElementById('last-updated');
    const viewOfflineContent = document.getElementById('view-offline-content');
    const cachedContent = document.querySelector('.c-offline__cached-content');
    const cachedPages = document.getElementById('cached-pages');

    // Update connection status
    function updateConnectionStatus() {
        if (navigator.onLine) {
            connectionStatus.textContent = '<?php esc_html_e('Your internet connection has been restored!', '_themename'); ?>';
            connectionStatus.className = 'status-online';
        } else {
            connectionStatus.textContent = '<?php esc_html_e('You are currently offline.', '_themename'); ?>';
            connectionStatus.className = 'status-offline';
        }
    }

    // Check for cached pages
    async function listCachedPages() {
        if ('caches' in window) {
            try {
                const cache = await caches.open('_themename_cache_v1');
                const keys = await cache.keys();
                const urls = keys.map(request => request.url)
                    .filter(url => url.endsWith('.html') || !url.includes('.'));

                if (urls.length > 0) {
                    const list = document.createElement('ul');
                    list.className = 'c-offline__cached-list';
                    
                    urls.forEach(url => {
                        const li = document.createElement('li');
                        const link = document.createElement('a');
                        link.href = url;
                        link.textContent = url.split('/').pop() || url;
                        li.appendChild(link);
                        list.appendChild(list);
                    });

                    cachedPages.innerHTML = '';
                    cachedPages.appendChild(list);
                } else {
                    cachedPages.innerHTML = '<p><?php esc_html_e('No content available offline.', '_themename'); ?></p>';
                }
            } catch (error) {
                console.error('Error accessing cache:', error);
                cachedPages.innerHTML = '<p><?php esc_html_e('Unable to access offline content.', '_themename'); ?></p>';
            }
        } else {
            viewOfflineContent.style.display = 'none';
        }
    }

    // Event listeners
    window.addEventListener('online', updateConnectionStatus);
    window.addEventListener('offline', updateConnectionStatus);
    
    viewOfflineContent.addEventListener('click', () => {
        cachedContent.style.display = cachedContent.style.display === 'none' ? 'block' : 'none';
        listCachedPages();
    });

    // Initial setup
    updateConnectionStatus();
    setInterval(() => {
        const now = new Date();
        lastUpdated.textContent = `<?php esc_html_e('Last checked:', '_themename'); ?> ${now.toLocaleTimeString()}`;
    }, 5000);
});
</script>

<style>
.c-offline {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    padding: 2rem;
    text-align: center;
}

.c-offline__content {
    max-width: 600px;
}

.c-offline__title {
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
    color: var(--color-text);
}

.c-offline__message {
    font-size: 1.125rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    color: var(--color-text-light);
}

.c-offline__status {
    margin-bottom: 2rem;
    padding: 1rem;
    border-radius: 8px;
    background: var(--color-background-alt);
}

.status-online {
    color: var(--color-success);
}

.status-offline {
    color: var(--color-warning);
}

.c-offline__actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 2rem;
}

.c-offline__cached-content {
    margin-top: 2rem;
    padding: 1rem;
    border-radius: 8px;
    background: var(--color-background-alt);
}

.c-offline__cached-list {
    list-style: none;
    padding: 0;
    margin: 1rem 0;
}

.c-offline__cached-list li {
    margin-bottom: 0.5rem;
}

.c-offline__cached-list a {
    color: var(--color-primary);
    text-decoration: none;
}

.c-offline__cached-list a:hover {
    text-decoration: underline;
}

#last-updated {
    font-size: 0.875rem;
    color: var(--color-text-light);
    margin-top: 0.5rem;
}

/* Button styles */
.c-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.c-button--primary {
    background-color: var(--color-primary);
    color: white;
}

.c-button--primary:hover {
    background-color: var(--color-primary-dark);
}

.c-button--secondary {
    background-color: var(--color-background);
    color: var(--color-text);
    border: 1px solid var(--color-border);
}

.c-button--secondary:hover {
    background-color: var(--color-background-alt);
}

@media (max-width: 480px) {
    .c-offline__actions {
        flex-direction: column;
    }
    
    .c-button {
        width: 100%;
    }
}
</style>

<?php get_footer(); ?>