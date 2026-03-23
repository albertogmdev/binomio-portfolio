<?php
/**
 * Featured Links Component Template
 */

$title = tcf_component($component, 'featured_links_title');
$subtitle = tcf_component($component, 'featured_links_subtitle');
$items = isset($component['featured_links_items']) && is_array($component['featured_links_items']) ? $component['featured_links_items'] : array();

$normalized_items = array();
foreach ($items as $item) {
    $name = trim(tcf_item($item, 'name'));
    $text = trim(tcf_item($item, 'text'));
    $link = trim(tcf_url($item['link'] ?? ''));
    $year = isset($item['year']) ? trim((string) $item['year']) : '';

    if ($name === '' && $text === '' && $link === '' && $year === '') {
        continue;
    }

    $normalized_items[] = array(
        'name' => $name,
        'text' => $text,
        'link' => $link,
        'year' => $year,
    );
}
?>

<section>
    <h1>Featured Links</h1>
</section>
