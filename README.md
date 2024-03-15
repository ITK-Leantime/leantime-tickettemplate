# Leantime Ticket Template

Allows administrators to create and configure a ticket templates per project.

## Installation

Clone this repository into your Leantime plugin folder:

``` shell
git clone https://github.com/ITK-Leantime/leantime-tickettemplate.git app/Plugins/TicketTemplate
```

Install and enable the plugin:

``` shell
bin/leantime plugin:install leantime/ticket-template
bin/leantime plugin:enable leantime/ticket-template
```

Alternatively, navigate to `/plugins/myapps` and activate the
`leantime/ticket-template` plugin.

## Usage

Go to plugin settings (`/TicketTemplate/settings`),
which requires at least administrator (40) role,
to configure ticket templates.

## Development

### Coding standards

``` shell
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer install
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer coding-standards-check
```
