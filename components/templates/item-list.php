<?php

/**
 * Section Item List Component Template
 */

$title = $component['hero_title'] ?? '';
?>

<section class="section-item_list">
    <div class="container">
        <div class="tabs">
            <div class="tab selected" data-panel="illustration" data-group="archive-list">Illustration</div>
            <div class="tab" data-panel="murals" data-group="archive-list">Murals</div>
            <div class="tab" data-panel="other" data-group="archive-list">Other</div>
        </div>
        <div id="illustration" class="content-panel archive-list">
            <ul class="item-list">
                <li>
                    <button href="#" class="link-info">
                        <div class="info-item info-item--first">FIDGTZILLA</div>
                        <div class="info-item info-item--second">Sculpture</div>
                        <div class="info-item info-item--third">2025</div>
                    </button>
                </li>
                <li>
                    <button href="#" class="link-info">
                        <div class="info-item info-item--first">MEMENTO MORI</div>
                        <div class="info-item info-item--second">Street Art</div>
                        <div class="info-item info-item--third">2024</div>
                    </button>
                </li>
                <li>
                    <button href="#" class="link-info">
                        <div class="info-item info-item--first">DEEP INSIDE</div>
                        <div class="info-item info-item--second">Sculpture</div>
                        <div class="info-item info-item--third">2023</div>
                    </button>
                </li>
                <li>
                    <button href="#" class="link-info">
                        <div class="info-item info-item--first">LEVIS JAPAN</div>
                        <div class="info-item info-item--second">Illustration</div>
                        <div class="info-item info-item--third">2023</div>
                    </button>
                </li>
                <li>
                    <button href="#" class="link-info">
                        <div class="info-item info-item--first">FIDGTZILLA</div>
                        <div class="info-item info-item--second">Sculpture</div>
                        <div class="info-item info-item--third">2025</div>
                    </button>
                </li>
                <li>
                    <button href="#" class="link-info">
                        <div class="info-item info-item--first">MEMENTO MORI</div>
                        <div class="info-item info-item--second">Street Art</div>
                        <div class="info-item info-item--third">2024</div>
                    </button>
                </li>
                <li>
                    <button href="#" class="link-info">
                        <div class="info-item info-item--first">DEEP INSIDE</div>
                        <div class="info-item info-item--second">Sculpture</div>
                        <div class="info-item info-item--third">2023</div>
                    </button>
                </li>
                <li>
                    <button href="#" class="link-info">
                        <div class="info-item info-item--first">LEVIS JAPAN</div>
                        <div class="info-item info-item--second">Illustration</div>
                        <div class="info-item info-item--third">2023</div>
                    </button>
                </li>
            </ul>
        </div>
        <div id="murals" class="content-panel hidden-panel archive-list">
            <h2 class="druk" style="margin-top: 20px; margin-bottom: 10px">Murals Items</h2>
            <ul class="items-list">
            </ul>
        </div>
        <div id="other" class="content-panel hidden-panel archive-list">
            <h2 class="druk" style="margin-top: 20px; margin-bottom: 10px">Other Items</h2>
            <ul class="items-list">
            </ul>
        </div>
    </div>

    <div id="archive-modal" class="modal modal-archive">
        <div class="modal-main">
            <button class="button button-icon modal-close">
                <span class="icon icon-close"></span>
            </button>
            <div class="modal-body theme--studio">
                <div class="modal-image">
                    <div class="decoration-row">
                        <span class="decoration decoration--topleft"></span>
                        <span class="decoration decoration--topright"></span>
                    </div>
                    <img
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/modal-test.jpg"
                        alt="" />
                    <div class="decoration-row">
                        <span class="decoration decoration--bottomleft"></span>
                        <span class="decoration decoration--bottomright"></span>
                    </div>
                </div>
                <div class="modal-content">
                    <p class="modal-title text-h2">Archive project</p>
                    <p class="modal-subtitle text-h4">Tag subtitle</p>
                    <p class="modal-description body-small">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc scelerisque varius nisi sit amet dapibus. Praesent interdum felis eget enim euismod, et fringilla massa aliquet. Mauris finibus porta porta. Aliquam ut purus ullamcorper, sollicitudin lacus vitae, iaculis diam. Mauris et faucibus velit. Aenean ullamcorper accumsan pharetra. Vivamus vel sem et velit posuere consectetur quis vitae metus. Ut rhoncus vestibulum tortor quis maximus.</br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc scelerisque varius nisi sit amet dapibus. Praesent interdum felis eget enim euismod, et fringilla massa aliquet. Mauris finibus porta porta. Aliquam ut purus ullamcorper, sollicitudin lacus vitae, iaculis diam. Mauris et faucibus velit. Aenean ullamcorper accumsan pharetra. Vivamus vel sem et velit posuere consectetur quis vitae metus. Ut rhoncus vestibulum tortor quis maximus.</p>
                    <div class="modal-buttons">
                        <a href="#" class="button">Buy it</a>
                    </div>
                </div>
            </div>
            <div class="modal-pagination theme--studio">
                <button class="pagination-item modal-prev">
                    <span class="icon icon-chevronleft"></span>
                    <span class="text">Prev</span>
                </button>
                <button class="pagination-item modal-next">
                    <span class="text">Next</span>
                    <span class="icon icon-chevronright"></span>
                </button>
            </div>
        </div>
    </div>
</section>