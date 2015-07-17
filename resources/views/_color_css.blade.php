<?php
        $color_one = "#" . Store::info()->colors->color_one;
        $color_two = "#" . Store::info()->colors->color_two;
        $color_three = "#" . Store::info()->colors->color_three;
        $color_four = "#" . Store::info()->colors->color_four;
        $color_five = "#" . Store::info()->colors->color_five;

        $color_one_dark = Utilities::adjustBrightness($color_one, -50);
        $color_two_dark = Utilities::adjustBrightness($color_two, -50);
        $color_three_dark = Utilities::adjustBrightness($color_three, -50);
        $color_four_dark = Utilities::adjustBrightness($color_four, -50);
        $color_five_dark = Utilities::adjustBrightness($color_five, -50);

        $color_one_light = Utilities::adjustBrightness($color_one, +50);
        $color_two_light = Utilities::adjustBrightness($color_two, +50);
        $color_three_light = Utilities::adjustBrightness($color_three, +50);
        $color_four_light = Utilities::adjustBrightness($color_four, +50);
        $color_five_light = Utilities::adjustBrightness($color_five, +50);
?>

{{-- To be included:
------------ footer
------------ .header .btn-primary
--}}

<style>
    .color-one {
        background: {{ $color_one }};
        color: #fff;
    }

    .border-color-one {
        border-color: {{ $color_one }};
    }

    .color-one-text {
        color: {{ $color_one }};
    }

    .color-two {
        color: {{ $color_two }};
    }

    .color-three {
        background: #fff;
        color: #4C4C4C;
    }

    .color-four {
        background: {{ $color_four }};
    }

    .section-title.color-five {
        background: {{ $color_five }};
        color:#FFF;
    }

    .color-one a {
        color: #fff;
    }

    .dark {
        color: #333 !important;
    }

    .btn:hover, .btn:focus {
        color: #fff;
    }

    .btn-one {
        background-color: #fff;
        color: {{ $color_one }};
        border-color: {{ $color_one_dark }};
    }

    .btn-one:hover, .btn-one:focus, .btn-one:active, .btn-one.active, .open .dropdown-toggle.btn-one {
        background-color: {{ $color_one_light }};
        color: #fff;
        border-color: {{ $color_one_light }};
    }

    .btn-three {
        color: #FFF;
        background-color: {{ $color_three }};
        border: 0;
        border-bottom: 4px solid {{ $color_three_dark }};
    }

    .btn-three:hover, .btn-three:focus, .btn-three:active, .btn-three.active, .open .dropdown-toggle.btn-three {
        background-color: {{ $color_three_light }};
        color: #fff;
        border-color: {{ $color_three_light }};
    }

    .indicator-down {
        border-top: 12px solid {{ $color_five }};
    }

    footer a {
        color: {{ $color_five_light  }}
    }
</style>

