<?php if (!defined('ABSPATH')) exit; ?>
<?php if ($permission_check): ?>

<?php endif; ?>
<?php include_once 'pagination.php'; ?>
<script>
jQuery(document).ready(function($) {
    $('.et-pagination-controls .et-per-page select').on('change', function(e) {
        e.preventDefault();
        const limit = $(this).val();
        let currentUrl = window.location.search;
        const urlParams = new URLSearchParams(currentUrl);
        if (urlParams.has('limit')) {
            urlParams.set('limit', limit);
        } else {
            urlParams.append('limit', limit);
        }
        urlParams.delete('offset');
        window.location.href = '?' + urlParams.toString();
    });

    $('.et-pagination-controls .et-pagination-buttons a').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        let currentUrl = window.location.search;
        const urlParams = new URLSearchParams(currentUrl);
        if (urlParams.has('offset')) {
            urlParams.set('offset', page);
        } else {
            urlParams.append('offset', page);
        }
        window.location.href = '?' + urlParams.toString();
    });
});
</script>
<?php endif; ?>