# Wordpress Starter 2023

Setup a simple Wordpress 6.x site powered by the Sage+Bootstrap-based [Thyme](https://github.com/tobyink/wp-thyme-theme) theme.

If you need to customize this base, fork this repo (and maybe Thyme's repo too) and use the URLs to your forked versions instead of the originals.

## Assumptions

* You have SSH access to a Linux+Apache web host
* You have a blank MySQL/MariaDB database
* PHP and PHP Composer are installed and working on the web host, along with any optional components Wordpress and Roots Sage need
* You have a domain name (or subdomain) pointed at the host
* Node JS, npm, and yarn are installed and working on the web host
* git is installed and working on the web host

## Begin

```shell
cd $HOME/sites/test.site.example
git clone https://github.com/tobyink/wp-base2023.git .
cp wp-config.php.example wp-config.php
$EDITOR wp-config.php
```

Change the `$domain` and optionally `$table_prefix`.

Change the database connection settings.

Change the crypto stuff to some freshly generated random strings.

Double check the rest looks sane.

We're in business.

## Basic Wordpress setup

1. Visit the site in your browser and run through Wordpress's usual procedure.
2. Go to the **Plugins** page in the backend and enable all the plugins.

Note: the Wordpress backend will be at `https://$domain/wordpress/wp-admin/`.
Notice the extra path component.

## Building the Thyme theme

If you're going to use a different theme, you can stop reading here.

```shell
cd $HOME/sites/test.site.example
cd wp-content/plugins/
git clone https://github.com/roots/acorn.git acorn
cd acorn
git checkout 2.x
composer install
cd ../../..
git clone https://github.com/tobyink/wp-thyme-theme.git thyme
cd wp-content/themes/
ln -s ../../thyme/ thyme
cd ../../thyme/
composer install
yarn install
yarn build
chmod -R ugo+rwX public/ resources/styles/common/_wp_theme.scss
```

## Theme setup

1. Return to the **Plugins** page in the Wordpress backend and enable the Acorn plugin.
2. Go to **Appearance** and switch to the Thyme theme.
3. Go to the **Sections** and **Theme Options** page in the backend, make a random change to the default values, and hit "Update" in each. This should force the theme CSS files to rebuild themselves.

