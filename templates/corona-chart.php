<?php
wp_enqueue_style( 'corona' );
wp_enqueue_script( 'corona' );
wp_enqueue_script( 'chartjs' );
$id = 'corona_chart_'.uniqid();
$getOptionHistory = get_option('corona_history');
$arrayCases = array_keys(get_object_vars($getOptionHistory->cases));
if (isset($data->timeline->cases)) {
    $arrayCases = array_keys(get_object_vars($data->timeline->cases));
}
$last_updated_utc = get_date_from_gmt(end($arrayCases) . ' 23:59');
$last_updated = strtotime($last_updated_utc); 
?>
<div class="corona-chart" >
    <canvas id="<?php echo esc_attr($id); ?>" 
        data-confirmed="<?php esc_attr_e($params['label_confirmed']); ?>"
        data-deaths="<?php esc_attr_e($params['label_deaths']); ?>"
        data-recovered="<?php esc_attr_e($params['label_recovered']); ?>"
        data-json="<?php esc_attr_e(json_encode($data)); ?>"
        data-country="<?php esc_attr_e($params['country']); ?>"
    ></canvas> 
    <?php if ($last_updated && $params['label_updated']) : ?>
        <div class="corona-updated">
            <?php
                echo esc_html($params['label_updated']);
                echo date_i18n(get_option('date_format') . ' - ' . get_option('time_format') . ' (P)', $last_updated); 
            ?>
        </div>
    <?php endif; ?>
</div>
