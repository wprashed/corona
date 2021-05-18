<?php
    wp_enqueue_style( 'corona' );
    $dataAll = new stdClass();
    $dataAll->cases = 0;
    $dataAll->deaths = 0;
    $dataAll->recovered = 0;
    $last_updated = get_option('corona_last_updated');
?>
<div class="corona-item corona-style-<?php echo esc_attr($params['style'] ? $params['style'] : 'default'); ?> corona-darkmode-<?php echo esc_attr($params['dark_mode']); ?> corona-<?php echo esc_attr($params['country'] ? 'country' : 'global'); ?>" >
    <h4 class="corona-title"><?php echo esc_html(isset($params['title']) ? $params['title'] : ''); ?></h4>
    <li class="corona-country">
        <div class=""></div>
        <div class="corona-country-stats corona-head">
            <div class="corona-col corona-confirmed">
                <div class="corona-label"><?php echo esc_html($params['label_confirmed']); ?></div>
            </div>
            <div class="corona-col corona-deaths">
                <div class="corona-label"><?php echo esc_html($params['label_deaths']); ?></div>
            </div>
            <div class="corona-col corona-recovered">
                <div class="corona-label"><?php echo esc_html($params['label_recovered']); ?></div>
            </div>
        </div>
    </li>
    <ul class="corona-list">
    <?php foreach ($data as $key => $value) : ?>
        <?php
        if ($value->country != 'World') :
            $dataAll->cases += $value->cases;
            $dataAll->deaths += $value->deaths;
            $dataAll->recovered += $value->recovered;
        ?>
        <li class="corona-country">
            <div class="">
                <?php if (isset($value->countryInfo->flag)) : ?>
                    <img width="15" class="corona-flag" src="<?php echo esc_html($value->countryInfo->flag); ?>" />    
                <?php endif; ?>
                <?php echo esc_html($value->country); ?>
            </div>
            <div class="corona-country-stats">
                <div class="corona-col corona-confirmed">
                    <div class="corona-value"><?php echo number_format($value->cases); ?></div>
                </div>
                <div class="corona-col corona-deaths">
                    <div class="corona-value"><?php echo number_format($value->deaths); ?></div>
                </div>
                <div class="corona-col corona-recovered">
                    <div class="corona-value"><?php echo number_format($value->recovered); ?></div>
                </div>
            </div>
        </li>
        <?php endif; ?>
    <?php endforeach; ?>
    </ul>
    <div class="corona-country corona-total">
        <div class=""><?php esc_html_e($params['label_total']); ?></div>
        <div class="corona-country-stats">
            <div class="corona-col corona-confirmed">
                <div class="corona-value"><?php echo number_format_i18n($dataAll->cases); ?></div>
            </div>
            <div class="corona-col corona-deaths">
                <div class="corona-value"><?php echo number_format_i18n($dataAll->deaths); ?></div>
            </div>
            <div class="corona-col corona-recovered">
                <div class="corona-value"><?php echo number_format_i18n($dataAll->recovered); ?></div>
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