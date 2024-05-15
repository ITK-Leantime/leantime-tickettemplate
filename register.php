<?php

use Leantime\Core\Application;
use Leantime\Core\Events;
use Leantime\Domain\Tickets\Models\Tickets;
use Leantime\Plugins\TicketTemplate\Services\TicketTemplate;

app()->resolving(Tickets::class, function (Tickets $ticket, Application $application) {
    $application->make(TicketTemplate::class)->processNewTicket($ticket);
});
