<?php
    get_header();
?>

<div class="container">
    <h1 class="druk">Design system</h1>
    <h2 class="druk" style="margin-top: 20px; margin-bottom: 10px">Button</h2>
    <button class="button">Click Me</button>
    <button class="button button-icon">
        <span class="text">Click Me</span>
        <span class="icon icon-arrowright"></span>
    </button>
    <a class="button button-icon" href="#">
        <span class="icon icon-arrowright"></span>
    </a>

    <h2 class="druk" style="margin-top: 20px; margin-bottom: 10px">Links</h2>
    <a class="link" href="#">Link</a>
    <a class="link" href="#">Link</a>

    <h2 class="druk" style="margin-top: 20px; margin-bottom: 10px">Tabs</h2>
    <div class="tabs">
        <div class="tab selected" href="#" data-group="test">Tab 1</div>
        <div class="tab" href="#" data-group="test">Tab 2</div>
        <div class="tab" href="#" data-group="test">Tab 3</div>
    </div>

    <h2 class="druk" style="margin-top: 20px; margin-bottom: 10px">Tags</h2>
    <div class="tag-list">
        <div class="tag">Sculpture</div>
        <div class="tag">Illustration</div>
        <div class="tag">Hand-made</div>
        <div class="tag">Painting</div>
        <div class="tag">Photography</div>
        <div class="tag">Design</div>
        <div class="tag">Limited</div>
    </div>

    <h2 class="druk" style="margin-top: 40px; margin-bottom: 10px">Components</h2>
    </div>
    <?php
        get_template_part( 'components/templates/item-list' );
    ?>
    <?php
        get_template_part( 'components/templates/hero' );
    ?>
    <?php
        get_template_part( 'components/templates/collection-grid' );
    ?>

<?php
    get_template_part( 'nav', 'below' );
    get_footer();
?>