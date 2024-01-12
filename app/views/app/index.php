<?php

    function content_body(): void {
        ?>

        <?php includePart('navbar'); ?>

        <section class="inner_body">

            <section class="inner__body_title">

                <h1>Mon H1 dans le body</h1>

            </section>

        </section>

        <?php
    }

    thisPageOnLayout('base');