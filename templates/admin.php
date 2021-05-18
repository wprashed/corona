<?php
$data = get_option('corona_countries');
?>

<div id="corona">
    <h1><?php esc_html_e('Documentation', 'corona'); ?></h1>
    <h2><?php esc_html_e('Shortcode [corona]', 'corona'); ?></h2>
    <select name="corona_countries">
        <option value=""><?php esc_html_e('========== Global ==========', 'corona'); ?></option>
        <?php
        foreach ($data as $item) {
            echo '<option value="'.$item->country.'">'.$item->country.'</option>';
        }
        ?>
    </select>
    <p>
    <code id="corona_shortcode"><?php esc_html_e('[corona]', 'corona'); ?></code>
    </p>
    <p><i><?php esc_html_e('Copy & paste this shortcode into post, page or widget.', 'corona'); ?></i></p>
    <h3><?php esc_html_e('Attributes', 'corona'); ?></h3>
    <ul class="corona-attributes">
        <li><strong><?php esc_html_e('country:', 'corona'); ?></strong> <?php esc_html_e('Country code', 'corona'); ?></li>
        <li><strong><?php esc_html_e('title:', 'corona'); ?></strong> <?php esc_html_e('Title of box - default is "Global"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_total:', 'corona'); ?></strong> <?php esc_html_e('Total text default is "Total"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_confirmed:', 'corona'); ?></strong> <?php esc_html_e('Label Confirmed - default is "Confirmed"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_deaths:', 'corona'); ?></strong> <?php esc_html_e('Label Deaths - default is "Deaths"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_recovered:', 'corona'); ?></strong> <?php esc_html_e('Label Recovered - default is "Recovered"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_active:', 'corona'); ?></strong> <?php esc_html_e('Label Active - default is "Active"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('style:', 'corona'); ?></strong> <?php esc_html_e('default, 2, 3, 4, 5, list'); ?></li>
        <li><strong><?php esc_html_e('dark_mode:', 'corona'); ?></strong> <?php esc_html_e('yes/no', 'corona'); ?></li>
        <li><strong><?php esc_html_e('continent:', 'corona'); ?></strong> <?php esc_html_e('Available: asia, africa, oceania, europe and americas', 'corona'); ?></li>
    </ul>
    <strong><?php esc_html_e('Example:', 'corona'); ?></strong><br/>
    <code>
    <?php esc_html_e('[corona country="Vietnam" title="Global" style="2" dark_mode="no" label_total="Total" label_confirmed="Confirmed" label_deaths="Deaths" label_recovered="Recovered"]', 'corona'); ?>
    </code>

    <h2><?php esc_html_e('Shortcode [corona-table]', 'corona'); ?></h2>
    <p><?php esc_html_e('This shortcode to display table with sorting, searching and pagination.', 'corona'); ?></p>
    <h3><?php esc_html_e('Attributes', 'corona'); ?></h3>
    <ul class="corona-attributes">
        <li><strong><?php esc_html_e('searching:', 'corona'); ?></strong> <?php esc_html_e('Show or hidden search box (value: true/false)', 'corona'); ?></li>
        <li><strong><?php esc_html_e('showing:', 'corona'); ?></strong> <?php esc_html_e('10, 25, 50 or 100'); ?></li>
        <li><strong><?php esc_html_e('label_confirmed:', 'corona'); ?></strong> <?php esc_html_e('Label Confirmed - default is "Confirmed"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_deaths:', 'corona'); ?></strong> <?php esc_html_e('Label Deaths - default is "Deaths"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_recovered:', 'corona'); ?></strong> <?php esc_html_e('Label Recovered - default is "Recovered"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_country:', 'corona'); ?></strong> <?php esc_html_e('Label Country - default is "Country and City"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('continent:', 'corona'); ?></strong> <?php esc_html_e('Available: asia, africa, oceania, europe and americas', 'corona'); ?></li>
        <li><strong><?php esc_html_e('country:', 'corona'); ?></strong> <?php esc_html_e('Country code', 'corona'); ?></li>
    </ul>

    <strong><?php esc_html_e('Example:', 'corona'); ?></strong><br/>
    <code>
    <?php esc_html_e('[corona-table searching="true" showing="10" label_confirmed="Confirmed" label_deaths="Deaths" label_recovered="Recovered"]', 'corona'); ?>
    </code>

    <h2><?php esc_html_e('Shortcode [corona-map]', 'corona'); ?></h2>
    <p><?php esc_html_e('This shortcode to display Coronavirus map', 'corona'); ?></p>
    <h3><?php esc_html_e('Attributes', 'corona'); ?></h3>
    <ul class="corona-attributes">
        <li><strong><?php esc_html_e('label_confirmed:', 'corona'); ?></strong> <?php esc_html_e('Label Confirmed - default is "Confirmed"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_deaths:', 'corona'); ?></strong> <?php esc_html_e('Label Deaths - default is "Deaths"', 'corona'); ?></li>
    </ul>

    <strong><?php esc_html_e('Example:', 'corona'); ?></strong><br/>
    <code>
    <?php esc_html_e('[corona-map label_confirmed="Confirmed" label_deaths="Deaths"]', 'corona'); ?>
    </code>

    <h2><?php esc_html_e('Shortcode [corona-line]', 'corona'); ?></h2>
    <p><?php esc_html_e('This shortcode to display line news', 'corona'); ?></p>
    <h3><?php esc_html_e('Attributes', 'corona'); ?></h3>
    <ul class="corona-attributes">
        <li><strong><?php esc_html_e('label_confirmed:', 'corona'); ?></strong> <?php esc_html_e('Label Confirmed - default is "Confirmed"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_deaths:', 'corona'); ?></strong> <?php esc_html_e('Label Deaths - default is "Deaths"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_recovered:', 'corona'); ?></strong> <?php esc_html_e('Label Recovered - default is "Recovered"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('country:', 'corona'); ?></strong> <?php esc_html_e('Country code', 'corona'); ?></li>
    </ul>

    <strong><?php esc_html_e('Example:', 'corona'); ?></strong><br/>
    <code>
    <?php esc_html_e('[corona-line label_confirmed="Confirmed" label_deaths="Deaths" label_recovered="Recovered"]', 'corona'); ?>
    </code>

    <h2><?php esc_html_e('Shortcode [corona-chart]', 'corona'); ?></h2>
    <p><?php esc_html_e('This shortcode to display chart history per country or global.', 'corona'); ?></p>
    <h3><?php esc_html_e('Attributes', 'corona'); ?></h3>
    <ul class="corona-attributes">
        <li><strong><?php esc_html_e('label_confirmed:', 'corona'); ?></strong> <?php esc_html_e('Label Confirmed - default is "Confirmed"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_deaths:', 'corona'); ?></strong> <?php esc_html_e('Label Deaths - default is "Deaths"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('label_recovered:', 'corona'); ?></strong> <?php esc_html_e('Label Recovered - default is "Recovered"', 'corona'); ?></li>
        <li><strong><?php esc_html_e('country:', 'corona'); ?></strong> <?php esc_html_e('Label Recovered - default is "Recovered"', 'corona'); ?></li>
    </ul>

    <strong><?php esc_html_e('Example:', 'corona'); ?></strong><br/>
    <code>
    <?php esc_html_e('[corona-chart label_confirmed="Confirmed" label_deaths="Deaths" label_recovered="Recovered"]', 'corona'); ?>
    </code>
</div>