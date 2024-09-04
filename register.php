<?php

use Leantime\Core\Bootstrap\Application;
use Leantime\Domain\Tickets\Models\Tickets;
use Leantime\Plugins\TicketTemplate\Services\TicketTemplate;

app()->resolving(Tickets::class, function (Tickets $ticket, Application $application) {
    $application->make(TicketTemplate::class)->processNewTicket($ticket);
});
