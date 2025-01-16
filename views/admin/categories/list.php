<?php
if (!defined('ABSPATH')) exit;

$categories = expense_tracker_init()->getModule('categories')->getCategories();
?>

<div class="wrap expense-tracker-warp">
    <h1 class="wp-heading-inline"><?php _e('Categories', 'expense-tracker'); ?></h1>

    <div class="postbox" style="padding: 15px; margin-top: 15px;">
        <form id="add-category-form" method="post">
            <input type="hidden" name="category_id" id="edit_category_id" value="">
            <h3 id="add-category-title"><?php _e('Add Category', 'expense-tracker'); ?></h3>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="category_name"><?php _e('Name', 'expense-tracker'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="category_name" class="regular-text" required>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="category_description"><?php _e('Description', 'expense-tracker'); ?></label>
                    </th>
                    <td>
                        <textarea name="description" id="category_description" class="large-text" rows="3"></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="category_budget"><?php _e('Budget', 'expense-tracker'); ?></label>
                    </th>
                    <td>
                        <input type="number" step="0.01" name="budget" id="category_budget" class="regular-text">
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Add Category', 'expense-tracker'), 'primary', 'submit', false); ?>
            <button type="button" id="cancel-edit" class="button" style="display:none;"><?php _e('Cancel Edit', 'expense-tracker'); ?></button>
        </form>
    </div>

    <div class="postbox" style="padding: 15px; margin-top: 15px;">
        <h3><?php _e('Existing Categories', 'expense-tracker'); ?></h3>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col"><?php _e('Name', 'expense-tracker'); ?></th>
                    <th scope="col"><?php _e('Description', 'expense-tracker'); ?></th>
                    <th scope="col"><?php _e('Budget', 'expense-tracker'); ?></th>
                    <th scope="col"><?php _e('Count', 'expense-tracker'); ?></th>
                    <th scope="col"><?php _e('Created By', 'expense-tracker'); ?></th>
                    <th scope="col"><?php _e('Updated By', 'expense-tracker'); ?></th>
                    <th scope="col"><?php _e('Created At', 'expense-tracker'); ?></th>
                    <th scope="col"><?php _e('Updated At', 'expense-tracker'); ?></th>
                    <th scope="col"><?php _e('Actions', 'expense-tracker'); ?></th>
                </tr>
            </thead>
            <tbody id="category-list">
                <?php if ($categories) : ?>
                    <?php foreach ($categories as $category) : ?>
                        <tr data-id="<?php echo esc_attr($category['id']); ?>">
                            <td><?php echo esc_html($category['name']); ?></td>
                            <td><?php echo esc_html($category['description']); ?></td>
                            <td><?php echo esc_html($category['budget']); ?></td>
                            <td>
                                <?php
                                $count = 0;
                                $expenses = expense_tracker_init()->getModule('expenses')->getExpenses(['category_id' => $category['id']]);
                                if ($expenses) {
                                    $count = count($expenses);
                                }
                                echo esc_html($count);
                                ?>
                            </td>
                            <td><?php echo esc_html($category['created_by']); ?></td>
                            <td><?php echo esc_html($category['updated_by']); ?></td>
                            <td><?php echo wp_date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($category['created_at'])); ?></td>
                            <td><?php echo wp_date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($category['updated_at'])); ?></td>
                            <td>
                                <a href="#" class="edit-category" data-id="<?php echo esc_attr($category['id']); ?>">
                                    <?php _e('Edit', 'expense-tracker'); ?>
                                </a> |
                                <a href="#" class="delete-category" data-id="<?php echo esc_attr($category['id']); ?>">
                                    <?php _e('Delete', 'expense-tracker'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9"><?php _e('No categories found.', 'expense-tracker'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {

        $('#add-category-form').on('submit', function(e) {
            e.preventDefault();

            const categoryId = $('#edit_category_id').val();
            const formData = $(this).serializeObject();
            let method = 'POST';
            let url = '<?php echo get_rest_url(null, 'expense-tracker/v1/categories'); ?>';

            if (categoryId) {
                method = 'PUT';
                url = '<?php echo get_rest_url(null, 'expense-tracker/v1/categories/'); ?>' + categoryId;
            }

            $.ajax({
                url: url,
                method: method,
                data: formData,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest'); ?>');
                },
                success: function(response) {
                    console.log('Category ' + (categoryId ? 'updated' : 'created') + ':', response);
                    if (categoryId) {
                        // Update the existing row
                        const updatedCategory = response;
                        const row = $(`tr[data-id="${categoryId}"]`);
                        row.find('td:eq(0)').text(updatedCategory.name);
                        row.find('td:eq(1)').text(updatedCategory.description);
                        row.find('td:eq(2)').text(updatedCategory.budget);
                        row.find('td:eq(4)').text(updatedCategory.created_by);
                        row.find('td:eq(5)').text(updatedCategory.updated_by);
                        row.find('td:eq(6)').text(updatedCategory.created_at);
                        row.find('td:eq(7)').text(updatedCategory.updated_at);
                        $('#edit_category_id').val('');
                        $('#cancel-edit').hide();
                        $('#add-category-form').find('input[type="submit"]').val('<?php _e('Add Category', 'expense-tracker'); ?>');
                        $('#add-category-title').text('<?php _e('Add Category', 'expense-tracker'); ?>');
                    } else {
                        // Add the new category to the table
                        const newCategory = response;
                        const newRow = $(`<tr data-id="${newCategory.id}">
                                        <td>${newCategory.name}</td>
                                        <td>${newCategory.description}</td>
                                        <td>${newCategory.budget}</td>
                                        <td>0</td>
                                        <td>${newCategory.created_by}</td>
                                        <td>${newCategory.updated_by}</td>
                                        <td>${newCategory.created_at}</td>
                                        <td>${newCategory.updated_at}</td>
                                        <td>
                                            <a href="#" class="edit-category" data-id="${newCategory.id}">
                                                <?php _e('Edit', 'expense-tracker'); ?>
                                            </a> |
                                            <a href="#" class="delete-category" data-id="${newCategory.id}">
                                                <?php _e('Delete', 'expense-tracker'); ?>
                                            </a>
                                        </td>
                                    </tr>`);
                        $('#category-list').append(newRow);
                    }
                    // Reset the form
                    $('#add-category-form')[0].reset();
                },
                error: function(error) {
                    console.error('Error ' + (categoryId ? 'updating' : 'creating') + ' category:', error);
                }
            });
        });
        $(document).on('click', '.edit-category', function(e) {
            e.preventDefault();
            const categoryId = $(this).data('id');
            const row = $(`tr[data-id="${categoryId}"]`);
            const name = row.find('td:eq(0)').text();
            const description = row.find('td:eq(1)').text();
            const budget = row.find('td:eq(2)').text();
            $('#add-category-title').text('<?php _e('Edit Category', 'expense-tracker'); ?>');


            $('#edit_category_id').val(categoryId);
            $('#category_name').val(name);
            $('#category_description').val(description);
            $('#category_budget').val(budget);
            $('#cancel-edit').show();
            $('#add-category-form').find('input[type="submit"]').val('<?php _e('Update Category', 'expense-tracker'); ?>');
        });

        $('#cancel-edit').on('click', function(e) {
            e.preventDefault();
            $('#edit_category_id').val('');
            $('#add-category-form')[0].reset();
            $('#cancel-edit').hide();
            $('#add-category-form').find('input[type="submit"]').val('<?php _e('Add Category', 'expense-tracker'); ?>');
            $('#add-category-title').text('<?php _e('Add Category', 'expense-tracker'); ?>');
        });

        $(document).on('click', '.delete-category', function(e) {
            e.preventDefault();
            const categoryId = $(this).data('id');

            if (confirm('Are you sure you want to delete this category?')) {
                $.ajax({
                    url: '<?php echo get_rest_url(null, 'expense-tracker/v1/categories/'); ?>' + categoryId,
                    method: 'DELETE',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest'); ?>');
                    },
                    success: function(response) {
                        console.log('Category deleted:', response);
                        // Remove the category from the list
                        $(`tr[data-id="${categoryId}"]`).remove();
                    },
                    error: function(error) {
                        console.error('Error deleting category:', error);
                    }
                });
            }
        });

        $.fn.serializeObject = function() {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
        // i remember once someone told me "lazy persons are smart persons"
        // after wasting two hour to format date and time, and checking every formet i remember i can change the format in the backend.
        function formatDateTime(dateTime) {
            let dateFormat = '<?php echo get_option('date_format'); ?>';
            let timeFormat = '<?php echo get_option('time_format'); ?>';
            const date = new Date(dateTime);
            // const month = String(date.getMonth() + 1).padStart(2, '0');
            // const monthName = date.toLocaleString(undefined, {
            //     month: 'long'
            // });
            const day = String(date.getDate()).padStart(2, '0');
            // alert(year + '-' + month + '-' + day);
            if (dateFormat.includes('Y')) {
                year = date.getFullYear();
            } else {
                dateFormat = dateFormat.replace('y', 'Y');
                year = String(date.getFullYear()).slice(-2);
            }
            if (dateFormat.includes('m')) {
                dateFormat = dateFormat.replace('m', 'M');
                month = String(date.getMonth() + 1).padStart(2, '0');
            } else if (dateFormat.includes('F')) {
                dateFormat = dateFormat.replace('F', 'M');
                month = date.toLocaleString(undefined, {
                    month: 'long'
                });
            } else {
                month = date.toLocaleString(undefined, {
                    month: 'short'
                });
                // alert(month);
            }
            dateFormat = dateFormat.replace('Y', year).replace('d', day).replace('j', day).replace('M', month);
            let formattedTime = date.toLocaleTimeString(undefined, {
                hour: 'numeric',
                minute: 'numeric',
                hour12: timeFormat.includes('H') ? false : true,
            });
            if (timeFormat.includes('a')) {
                formattedTime = formattedTime.replace('AM', 'am').replace('PM', 'pm');
            }

            return dateFormat + ' ' + formattedTime;
        }
    });
</script>