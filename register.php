<?php

use Leantime\Core\Application;
use Leantime\Core\Events;
use Leantime\Domain\Tickets\Models\Tickets;
use Leantime\Plugins\DefaultTicketTemplate\Services\DefaultTicketTemplate;

app()->resolving(Tickets::class, function (Tickets $ticket, Application $application) {
    $application->make(DefaultTicketTemplate::class)->processNewTicket($ticket);
});

// Add plugin translation file,
// see @Leantime\Plugins\DefaultTicketTemplate\Controllers:get(),
// for how we ensure these are read.
Events::add_filter_listener(
    'leantime.core.language.readIni.language_files',
    function (array $payload, array $context): array {
        $language = $context['language'];
        $languageFile = __DIR__ . '/Language/' . $language . '.ini';
        if (file_exists($languageFile)) {
            $payload[$languageFile] = 'en-US' !== $language;
        }

        return $payload;
    }
);
