<div class="et-pagination">
    <?php
    // $total_items = 99;
    // $per_page = 10;
    $total_pages = ceil($total_items / $per_page);
    $current_page = isset($_GET['p']) ? intval($_GET['p']) : 1;
    $url_params = $_GET;
    ?>

    <div class="et-pagination-controls">
        <div class="et-per-page">
            <label for="per_page"><?php echo esc_html__('Per Page', 'expense-tracker'); ?></label>
            <select class="">
                <option value="10" <?php selected($per_page, 10); ?>>10</option>
                <option value="25" <?php selected($per_page, 25); ?>>25</option>
                <option value="50" <?php selected($per_page, 50); ?>>50</option>
                <option value="100" <?php selected($per_page, 100); ?>>100</option>
            </select>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="et-pagination-buttons">
                <?php
                // First page
                $url_params['p'] = 1;
                ?>
                <a href="#" data-page="1" class="button <?php echo $current_page == 1 ? 'disabled' : ''; ?>"
                    <?php echo $current_page == 1 ? 'disabled' : ''; ?>>
                    <span class="dashicons dashicons-controls-skipback"></span>
                </a>

                <?php
                // Previous page
                ?>
                <a href="#" data-page="<?php echo max(1, $current_page - 1); ?>"
                    class="button <?php echo $current_page == 1 ? 'disabled' : ''; ?>"
                    <?php echo $current_page == 1 ? 'disabled' : ''; ?>>
                    <span class="dashicons dashicons-controls-back"></span>
                </a>

                <?php
                // Page numbers
                //     $start = max(1, min($current_page - 2, $total_pages - 4));
                // $end = min($total_pages, max(5, $current_page + 2));

                for ($i = 1; $i <= $total_pages; $i++) {
                    $url_params['p'] = $i;
                ?>
                    <a href="#" data-page="<?php echo $i; ?>"
                        class="button <?php echo $current_page == $i ? 'disabled" disabled' : '"'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php
                }

                // Next page
                $url_params['p'] = min($total_pages, $current_page + 1);
                ?>
                <a href="#" data-page="<?php echo min($total_pages, $current_page + 1); ?>"
                    class="button <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>"
                    <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>>
                    <span class="dashicons dashicons-controls-forward"></span>
                </a>

                <?php
                ?>
                <a href="#" data-page="<?php echo $total_pages; ?>"
                    class="button <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>"
                    <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>>
                    <span class="dashicons dashicons-controls-skipforward"></span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>