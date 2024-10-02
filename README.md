# WP-ACF-Map-Pins
QoL plugin for WordPress with Advanced Custom Fields, for creating markers on maps

## How it works
Used in conjuction with ACF and repeater fields, for case when you have a map and client needs to put markers on some custom map image on the frontend (say, region map, where projects been done, etc). Guess I should provide an example.

## How to use
- Put in the plugins directory. Currently all the things are hardcoded, change things per your needs.
- Field name must be: "map"
- Field must be a repeater
- Coordinates fields must be called "map_marker_pos_x" and "map_marker_pos_y"
- If everything's good, you'll see map displayed just below the repeater field. Click on the map and magic happens, it will place a marker in place where you clicked and fill position fields with percentage value (basically CSS top and left properties).
- ?????
- You're magnificent. (c)