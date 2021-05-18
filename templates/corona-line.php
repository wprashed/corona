<?php
wp_enqueue_style( 'corona' );
$last_updated = get_option('corona_last_updated');
?>
<div class="corona-line" >
    <div class="corona-line-wrap">
        <div class="corona-line-colour corona-slideup corona-animated"></div>  
        <div class="corona-line-title corona-slidein">
        <?php 
        if (isset($data->country)) {
            echo esc_html($params['title'] !== "Global" ? $params['title'] : $data->country);
        } else {
            echo esc_html(isset($params['title']) ? $params['title'] : '');
        }
        ?>
        </div> 
        <div class="corona-line-headline corona-fadein corona-marquee">
            <span class="line--confirmed">
                <?php echo esc_html($params['label_confirmed']); ?>
                <span class="line--value">
                    <?php echo number_format($data->cases); ?>
                    <span class="line--today">
                        <?php 
                        if (isset($data->todayCases) && $data->todayCases > 0) {
                            echo '+'.number_format($data->todayCases) .' <span class="line--new">'.esc_html($params['label_new']).'</span>';
                        }
                        ?>
                    </span>
                </span>
            </span>

            <span class="line--deaths">
                <?php echo esc_html($params['label_deaths']); ?>
                <span class="line--value">
                    <?php echo number_format($data->deaths); ?>
                    <span class="line--today">
                        <?php 
                        if (isset($data->todayDeaths) && $data->todayDeaths > 0) {
                            echo '+'.number_format($data->todayDeaths) .' <span class="line--new">'.esc_html($params['label_new']).'</span>';
                        }
                        ?>
                    </span>
                </span>
            </span>

            <span class="line--recovered">
                <?php echo esc_html($params['label_recovered']); ?>
                <span class="line--value">
                    <?php echo number_format($data->recovered); ?>
                </span>
            </span>

            <span class="line--active">
                <?php echo esc_html($params['label_active']); ?>
                <span class="line--value">
                    <?php echo number_format($data->active); ?>
                </span>
            </span>

            <?php if ($last_updated && $params['label_updated']) : ?>
                <span class="line--updated">
                    <?php
                        echo esc_html($params['label_updated']);
                        echo date_i18n(get_option('date_format') . ' - ' . get_option('time_format') . ' (P)', $last_updated); 
                    ?>
                </span>
            <?php endif; ?>
        </div>  
    </div>  
</div>