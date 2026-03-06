<?php
/**
 * Featured Links Component Template
 */

$title = isset($component['featured_links_title']) ? (string) $component['featured_links_title'] : '';
$subtitle = isset($component['featured_links_subtitle']) ? (string) $component['featured_links_subtitle'] : '';
$items = isset($component['featured_links_items']) && is_array($component['featured_links_items']) ? $component['featured_links_items'] : array();

$normalized_items = array();
foreach ($items as $item) {
    $name = isset($item['name']) ? trim((string) $item['name']) : '';
    $text = isset($item['text']) ? trim((string) $item['text']) : '';
    $link = isset($item['link']) ? trim((string) $item['link']) : '';
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
