# Leantime Default Ticket Template

Allows administrators to configure a default ticket template per project.

## Installation

Clone this repository into your Leantime plugin folder:

``` shell
git clone https://github.com/ITK-Leantime/leantime-defaulttickettemplate.git app/Plugins/DefaultTicketTemplate
```

Install and enable the plugin:

``` shell
bin/leantime plugin:install leantime/default-ticket-template
bin/leantime plugin:enable leantime/default-ticket-template
```

Alternatively, navigate to `/plugins/myapps` and activate the
`leantime/default-ticket-template` plugin.

## Usage

Go to plugin settings (`/DefaultTicketTemplate/settings`),
which requires at least administrator (40) role,
to configure default templates.

## Development

### Coding standards

``` shell
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer install
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer coding-standards-check
```
