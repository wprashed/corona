<?php
wp_enqueue_style( 'corona' );
$last_updated = get_option('corona_last_updated');
$dataObj = new stdClass();
$dataObj->cases = 0;
$dataObj->deaths = 0;
$dataObj->recovered = 0;
$dataObj->todayCases = 0;
$dataObj->todayDeaths = 0;
$dataObj->active = 0;

if (is_array($data)) {
    foreach ($data as $key => $value) {
        $dataObj->cases += $value->cases;
        $dataObj->deaths += $value->deaths;
        $dataObj->recovered += $value->recovered;
        $dataObj->todayCases += $value->todayCases;
        $dataObj->todayDeaths += $value->todayDeaths;
        $dataObj->active += $value->active;
    }
} else {
    $dataObj->cases += $data->cases;
    $dataObj->deaths += $data->deaths;
    $dataObj->recovered += $data->recovered;
    $dataObj->todayCases += isset($data->todayCases) ? $data->todayCases : 0;
    $dataObj->todayDeaths += isset($data->todayDeaths) ? $data->todayDeaths : 0;
    $dataObj->active += isset($data->active) ? $data->active : 0;
}

?>
<div class="corona-item corona-style-<?php echo esc_attr($params['style'] ? $params['style'] : 'default'); ?> corona-darkmode-<?php echo esc_attr($params['dark_mode']); ?> corona-<?php echo esc_attr($params['country'] ? 'country' : 'global'); ?>" >
    <div class="corona-inner">
        <h4 class="corona-title">
            <?php if (isset($data->countryInfo->flag)) : ?>
                <img width="30" class="title-flag" src="<?php echo esc_html($data->countryInfo->flag); ?>" />    
            <?php endif; ?>
            <?php echo esc_html(isset($params['title']) ? $params['title'] : ''); ?>
        </h4>
        <div class="corona-row">
            <div class="corona-col corona-confirmed">
                <div class="corona-label"><?php echo esc_html($params['label_confirmed']); ?></div>
                <div class="corona-value">
                    <?php echo number_format($dataObj->cases); ?>
                    <div class="new-today">
                        <?php 
                        if (isset($dataObj->todayCases) && $dataObj->todayCases > 0) {
                            echo '+'.number_format($dataObj->todayCases) .' <span class="new-label">'.esc_html($params['label_new']).'</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="corona-col corona-deaths">
                <div class="corona-label"><?php echo esc_html($params['label_deaths']); ?></div>
                <div class="corona-value">
                    <?php echo number_format($dataObj->deaths); ?>
                    <div class="new-today">
                        <?php 
                        if (isset($dataObj->todayDeaths) && $dataObj->todayDeaths > 0) {
                            echo '+'.number_format($dataObj->todayDeaths) .' <span class="new-label">'.esc_html($params['label_new']).'</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="corona-col corona-recovered">
                <div class="corona-label"><?php echo esc_html($params['label_recovered']); ?></div>
                <div class="corona-value"><?php echo number_format($dataObj->recovered); ?></div>
            </div>
            <div class="corona-col corona-active">
                <div class="corona-label"><?php echo esc_html($params['label_active']); ?></div>
                <div class="corona-value"><?php echo number_format($dataObj->active); ?></div>
            </div>
        </div>
    </div>

    <?php if ($last_updated && $params['label_updated']) : ?>
        <div class="corona-updated">
            <?php
                echo esc_html($params['label_updated']);
                echo date_i18n(get_option('date_format') . ' - ' . get_option('time_format') . ' (P)', $last_updated); 
            ?>
        </div>
    <?php endif; ?>
</div>