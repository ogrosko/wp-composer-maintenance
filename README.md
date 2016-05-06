# wp-composer-maintenance

When you are using [Wordpress with composer](https://roots.io/using-composer-with-wordpress/) this scripts automatically put you website to maintenance mode when you run `composer update`.
wp-composer-maintenance are collection of simple scripts for composer pre-update/post-update section.

## Installation using [Composer](http://getcomposer.org/)

```bash
$ composer require ogrosko/wp-composer-maintenance
```

## Usage
Add following config in `scripts` section of your `composer.json` file

```json
"scripts": {
	"pre-update-cmd": [
		"OGrosko\\Composer\\WpComposerMaintenance::maintenance_enable"
	],
	"post-update-cmd": [
		"OGrosko\\Composer\\WpComposerMaintenance::maintenance_disable"
	]
},
```