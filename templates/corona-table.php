<?php
wp_enqueue_style( 'corona' );
wp_enqueue_style( 'jquery.datatables' );
wp_enqueue_script( 'jquery.datatables' );
$id = 'corona_table_'.uniqid();
$detail = ($params['show_detail'] && !$params['country']) ? true : false;
$last_updated = get_option('corona_last_updated');
?>
<div class="corona-table">
    <table id="<?php echo esc_attr($id); ?>" data-page-length="<?php echo esc_attr($params['showing']); ?>">
        <thead>
            <tr>
                <th><?php echo esc_html($params['label_country']); ?></th>
                <th><?php echo esc_html($params['label_confirmed']); ?></th>
                <?php if ($detail) : ?>
                    <th><?php echo esc_html($params['label_confirmedtoday']); ?></th>
                <?php endif; ?>
                <th><?php echo esc_html($params['label_deaths']); ?></th>
                <?php if ($detail) : ?>
                    <th><?php echo esc_html($params['label_deathstoday']); ?></th>
                <?php endif; ?>
                <th><?php echo esc_html($params['label_recovered']); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $key => $value) : ?>
        <?php
            $value->country = isset($value->country) ? $value->country : ($value->admin2 ? $value->admin2 : $value->provinceState);
            $value->cases = isset($value->cases) ? $value->cases : $value->confirmed;
        ?>
            <tr>
                <td>
                    <?php if (isset($value->countryInfo->flag)) : ?>
                        <img width="15" class="corona-flag" src="<?php echo esc_html($value->countryInfo->flag); ?>" />    
                    <?php endif; ?>
                    <?php echo esc_html($value->country); ?>
                </td>
                <td><?php echo number_format($value->cases); ?></td>
                <?php if ($detail) : ?>
                    <td><?php echo number_format($value->todayCases); ?></td>
                <?php endif; ?>
                <td><?php echo number_format($value->deaths); ?></td>
                <?php if ($detail) : ?>
                    <td><?php echo number_format($value->todayDeaths); ?></td>
                <?php endif; ?>
                <td><?php echo number_format($value->recovered); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($last_updated && $params['label_updated']) : ?>
        <div class="corona-updated">
            <?php
                echo esc_html($params['label_updated']);
                echo date_i18n(get_option('date_format') . ' - ' . get_option('time_format') . ' (P)', $last_updated); 
            ?>
        </div>
    <?php endif; ?>
    <script>
        jQuery(document).ready(function($) {
            $('#<?php echo esc_attr($id); ?>').DataTable({
                "order": [[ 1, "desc" ]],
                "searching": <?php echo esc_attr($params['searching']); ?>,
                "language": {
                    "url": "<?php echo esc_url($params['lang_url']); ?>"
                }
            });
        } );
    </script>
</div>