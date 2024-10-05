# WP-ACF-Map-Pins
QoL plugin for WordPress with Advanced Custom Fields, for creating markers on maps

## How it works
Used in conjuction with ACF and repeater fields, for case when you have a map and client needs to put markers on some custom map image on the frontend (say, region map, where projects been done, etc). Guess I should provide an example.

## How to use
Drop it into plugins directory and activate.
If you haven't created ACF repeater for markers, that's probably the right time to do it. 
Change map settings accordingly.
If everything done correct, map should appear right below the repeater. Create a field, click - viola, coordinates in corresponding fields. Take these values and assing to top and left CSS properties.

### Settings take few parameters:
- Field name, this code tries to find your repeater fields by parent field name.
- Coordinates X / Y fields names, for updating these values (in percents) on map click.
- Map image. There's an *Ace Combat Strangereal map* fallback because why not.
- Existing pin image. There's a low-res random pin fallback.
- New pin image. There's a low-res random pin fallback. Same pin, different color, just to let you know it's the one you placed just now.

It's not the best readme, but hey, I try.