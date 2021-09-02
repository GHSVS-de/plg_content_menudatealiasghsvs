# plg_content_menudatealiasghsvs
Joomla content plugin. Bring back datetime alias for menu items of type System Links > Menu Item Alias.

## Why this plugin?
See https://github.com/joomla/joomla-cms/pull/10756

### Before Joomla 3.6.0
When you saved a new menu item of type `Menu Item Alias` and left the field `Alias` empty an alias was created automatically based upon current date and time (e.g. `2016-07-29-20-08-51`).

### Since Joomla 3.6.0
The behavior has changed and the automatic alias is based upon the `Title` field (e.g. Title "Home of This" results in `home-of-this`).

### This plugin brings back the old behavior.

The "old-fashioned" date-time alias is created automatically by this plugin

    only for NEW menu items (saved for the first time). NEW: You can change this behavior in plugin settings.
    only for menu items of type Menu Item Alias.
    only if field Alias is left empty.
    only in back-end of Joomla.
    only if Joomla version is equal/greater than 3.6.0.

**Enter an empty space in Alias field if you want the automatic Joomla behavior (Alias based upon Title field) while saving.**

### German Description / Deutsche Beschreibung
https://www.ghsvs.de/programmierer-schnipsel/joomla/189-plugin-menuetyp-menuealias-alias-wieder-zeitstempel

### Changelogs
https://updates.ghsvs.de/changelog.php?file=menudatealiasghsvs

-----------------------------------------------------

# My personal build procedure (WSL 1, Debian, Win 10)
- Prepare/adapt `./package.json`.
- `cd /mnt/z/git-kram/plg_content_menudatealiasghsvs`

## node/npm updates/installation
- `npm run g-npm-update-check` or (faster) `ncu`
- `npm run g-ncu-override-json` (if needed) or (faster) `ncu -u`
- `npm install` (if needed)

## Build installable ZIP package
- `node build.js`
- New, installable ZIP is in `./dist` afterwards.
- All packed files for this ZIP can be seen in `./package`. **But only if you disable deletion of this folder at the end of `build.js`**.

### For Joomla update and changelog server
- Create new release with new tag.
- - See release description in `dist/release.txt`.
- Extracts(!) of the update and changelog XML for update and changelog servers are in `./dist` as well. Copy/paste and necessary additions.
