<?php

/**
 * Collection Grid Component Template
 */

$title = $component['hero_title'] ?? '';
?>

<section class="section-collection_grid">
    <div class="container">
        <div class="tabs">
            <div class="tab selected" data-panel="projects">Projects</div>
            <div class="tab" data-panel="exhibitions">Exhibitions</div>
        </div>
        <div id="projects" class="content-panel">
            <div class="items-grid">
                <div class="collection-card item item--onecol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test2.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">fightzilla</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="/hola" class="button item-button">See project</a>
                    </div>
                </div>
                <div class="collection-card item item--onecol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test3.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">memento mori</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="#" class="button item-button">See project</a>
                    </div>
                </div>
                <div class="collection-card item item--twocol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test1.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">Deep inside</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="#" class="button item-button">See project</a>
                    </div>
                </div>
                <div class="collection-card item item--twocol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test1.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">Deep inside</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="#" class="button item-button">See project</a>
                    </div>
                </div>
                <div class="collection-card item item--twocol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test1.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">Deep inside</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="#" class="button item-button">See project</a>
                    </div>
                </div>
                <div class="collection-card item item--onecol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test2.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">fightzilla</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="#" class="button item-button">See project</a>
                    </div>
                </div>
                <div class="collection-card item item--onecol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test3.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">memento mori</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="#" class="button item-button">See project</a>
                    </div>
                </div>
                <div class="collection-card item item--onecol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test2.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">fightzilla</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="#" class="button item-button">See project</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="exhibitions" class="content-panel hidden-panel">
            <div class="items-grid">
                <div class="collection-card item item--onecol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test2.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">fightzilla</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="#" class="button item-button">See project</a>
                    </div>
                </div>
                <div class="collection-card item item--onecol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test3.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">memento mori</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="#" class="button item-button">See project</a>
                    </div>
                </div>
                <div class="collection-card item item--twocol">
                    <img
                        class="card-image"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/collection-test1.png"
                        alt="Collection Card Placeholder">
                    <div class="card-info">
                        <h3 class="item-title">Deep inside</h3>
                        <p class="item-description">DATA: JULY 25</br>Limited Edition 3/3 of this Sofubi call Fightzilla</p>
                        <a href="#" class="button item-button">See project</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>