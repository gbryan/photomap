<?php

Marker::observe(new PhotoMap\Observers\ValidationObserver);
Photo::observe(new PhotoMap\Observers\ValidationObserver);
