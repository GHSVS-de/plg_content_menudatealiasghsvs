# plg_content_menudatealiasghsvs
Joomla plugin. Bring back datetime alias for menu items of type System Links > Menu Item Alias

## Temporarily download link
https://updates.ghsvs.de/joomla/plg_content_menudatealiasghsvs/plg_content_menudatealiasghsvs_current.zip

## Why this plugin?
See https://github.com/joomla/joomla-cms/pull/10756

### Before Joomla 3.6.0
When you saved a new menu item of type `Menu Item Alias` and left the field `Alias` empty an alias was created automatically based upon current date and time (e.g. `2016-07-29-20-08-51`).

### Since Joomla 3.6.0
The behavior has changed and the automatic alias is based upon the `Title` field (e.g. Title "Home of This" results in `home-of-this`).

### This plugin brings partially back the old behavior.

The "old-fashioned" date-time alias is created automatically by this plugin

    only for NEW menu items (saved for the first time).
    only for menu items of type Menu Item Alias.
    only if field Alias is left empty.
    only in back-end of Joomla.
    only if Joomla version is equal/greater than 3.6.0.

**Enter an empty space in Alias field if you want the automatic Joomla behavior (Alias based upon Title field) while saving.**
