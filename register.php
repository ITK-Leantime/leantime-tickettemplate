<?php

use Leantime\Core\Application;
use Leantime\Domain\Tickets\Models\Tickets;
use Leantime\Plugins\DefaultTicketTemplate\Services\DefaultTicketTemplate;

app()->resolving(Tickets::class, function (Tickets $ticket, Application $application) {
    $application->make(DefaultTicketTemplate::class)->processNewTicket($ticket);
});
