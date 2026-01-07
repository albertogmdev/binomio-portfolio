<?php
    get_header();
?>

<div class="container">
    <h1>Design system</h1>
    <h2 style="margin-top: 20px; margin-bottom: 10px">Button</h1>
    <button class="button">Click Me</button>
    <button class="button button-icon">
        <span class="text">Click Me</span>
        <span class="icon icon-arrowright"></span>
    </button>
    <a class="button button-icon" href="#">
        <span class="icon icon-arrowright"></span>
    </a>

    <h2 style="margin-top: 20px; margin-bottom: 10px">Links</h2>
    <a class="link" href="#">Link</a>
    <a class="link" href="#">Link</a>

    <h2 style="margin-top: 20px; margin-bottom: 10px">Tabs</h2>
    <div class="tabs">
        <a class="tab selected" href="#">Tab 1</a>
        <a class="tab" href="#">Tab 2</a>
        <a class="tab" href="#">Tab 3</a>
    </div>
</div>

<?php
    get_template_part( 'nav', 'below' );
    get_footer();
?>