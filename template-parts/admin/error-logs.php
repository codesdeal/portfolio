<div class="wrap">
    <h1><?php esc_html_e('Theme Error Logs', '_themename'); ?></h1>
    
    <div class="error-logs-container">
        <?php if (empty($errors)): ?>
            <p><?php esc_html_e('No errors logged.', '_themename'); ?></p>
        <?php else: ?>
            <div class="tablenav top">
                <div class="alignleft actions">
                    <select id="error-type-filter">
                        <option value=""><?php esc_html_e('All Types', '_themename'); ?></option>
                        <option value="runtime"><?php esc_html_e('Runtime Errors', '_themename'); ?></option>
                        <option value="unhandledrejection"><?php esc_html_e('Unhandled Rejections', '_themename'); ?></option>
                        <option value="resource"><?php esc_html_e('Resource Errors', '_themename'); ?></option>
                        <option value="performance"><?php esc_html_e('Performance Issues', '_themename'); ?></option>
                    </select>
                    <button class="button" id="clear-filters"><?php esc_html_e('Clear Filters', '_themename'); ?></button>
                </div>
            </div>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th scope="col"><?php esc_html_e('Timestamp', '_themename'); ?></th>
                        <th scope="col"><?php esc_html_e('Type', '_themename'); ?></th>
                        <th scope="col"><?php esc_html_e('Message', '_themename'); ?></th>
                        <th scope="col"><?php esc_html_e('URL', '_themename'); ?></th>
                        <th scope="col"><?php esc_html_e('Details', '_themename'); ?></th>
                    </tr>
                </thead>
                <tbody id="error-logs-list">
                    <?php foreach ($errors as $error): ?>
                        <tr data-error-type="<?php echo esc_attr($error['type']); ?>">
                            <td><?php echo esc_html($error['timestamp']); ?></td>
                            <td><?php echo esc_html(ucfirst($error['type'])); ?></td>
                            <td><?php echo esc_html($error['message']); ?></td>
                            <td><?php echo esc_html($error['url']); ?></td>
                            <td>
                                <button class="button view-details" data-error="<?php echo esc_attr(wp_json_encode($error)); ?>">
                                    <?php esc_html_e('View Details', '_themename'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div id="error-details-modal" class="error-modal">
        <div class="error-modal-content">
            <span class="close">&times;</span>
            <h2><?php esc_html_e('Error Details', '_themename'); ?></h2>
            <div id="error-details"></div>
        </div>
    </div>
</div>

<style>
.error-modal {
    display: none;
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
}

.error-modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 800px;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
    border-radius: 4px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

#error-details {
    margin-top: 20px;
}

#error-details pre {
    background: #f5f5f5;
    padding: 10px;
    overflow-x: auto;
    margin: 10px 0;
    border-radius: 4px;
}

.performance-metrics {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.metric-card {
    background: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 4px;
}

.metric-card h4 {
    margin: 0 0 8px 0;
    color: #23282d;
}

.error-type-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.error-type-runtime { background: #fee2e2; color: #991b1b; }
.error-type-unhandledrejection { background: #fef3c7; color: #92400e; }
.error-type-resource { background: #e0e7ff; color: #3730a3; }
.error-type-performance { background: #dcfce7; color: #166534; }
</style>

<script>
jQuery(document).ready(function($) {
    const modal = document.getElementById('error-details-modal');
    const closeBtn = document.querySelector('.close');
    const errorDetails = document.getElementById('error-details');
    
    // Filter functionality
    $('#error-type-filter').on('change', function() {
        const selectedType = $(this).val();
        $('#error-logs-list tr').each(function() {
            const errorType = $(this).data('error-type');
            $(this).toggle(!selectedType || errorType === selectedType);
        });
    });

    $('#clear-filters').on('click', function() {
        $('#error-type-filter').val('');
        $('#error-logs-list tr').show();
    });

    // Modal functionality
    $('.view-details').on('click', function() {
        const error = JSON.parse($(this).data('error'));
        let detailsHtml = `
            <h3>Error Information</h3>
            <div class="error-type-badge error-type-${error.type}">${error.type}</div>
            <pre>${JSON.stringify(error, null, 2)}</pre>
        `;

        if (error.performance) {
            detailsHtml += `
                <h3>Performance Metrics</h3>
                <div class="performance-metrics">
                    ${renderPerformanceMetrics(error.performance)}
                </div>
            `;
        }

        errorDetails.innerHTML = detailsHtml;
        modal.style.display = 'block';
    });

    closeBtn.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    function renderPerformanceMetrics(performance) {
        let html = '';
        
        if (performance.navigation) {
            html += `
                <div class="metric-card">
                    <h4>Navigation Timing</h4>
                    <p>DOM Complete: ${Math.round(performance.navigation.domComplete)}ms</p>
                    <p>DOM Interactive: ${Math.round(performance.navigation.domInteractive)}ms</p>
                    <p>Load Complete: ${Math.round(performance.navigation.loadEventEnd)}ms</p>
                </div>
            `;
        }

        if (performance.paint) {
            html += `
                <div class="metric-card">
                    <h4>Paint Timing</h4>
                    <p>First Paint: ${Math.round(performance.paint['first-paint'] || 0)}ms</p>
                    <p>First Contentful Paint: ${Math.round(performance.paint['first-contentful-paint'] || 0)}ms</p>
                </div>
            `;
        }

        if (performance.memory) {
            html += `
                <div class="metric-card">
                    <h4>Memory Usage</h4>
                    <p>Used JS Heap: ${formatBytes(performance.memory.usedJSHeapSize)}</p>
                    <p>Total JS Heap: ${formatBytes(performance.memory.totalJSHeapSize)}</p>
                </div>
            `;
        }

        return html;
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>