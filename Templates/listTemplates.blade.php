@extends($layout)

@section('content')

    <x-global::pageheader :icon="'fa fa-puzzle-piece'">
        <h1>{{ __("tickettemplate.list.page_header") }}</h1>
    </x-global::pageheader>

    <div class="maincontent">
        <?php echo $tpl->displayNotification(); ?>
        <div class="maincontentinner">
            <div class="row" style="margin-left: 0; margin-right: 0;">
                <div class="column">
                    <h4 class="widgettitle title-light"><span class="fa fa-cog"></span>
                        {{ __("tickettemplate.list.widget_title") }}
                    </h4>
                </div>
                <div class="column">
                    <div class="" style="float:right;">
                        <a href="<?php echo BASE_URL; ?>/TicketTemplate/createTemplate" class="btn btn-primary"><i class="fa-solid fa-plus"></i> {{ __('tickettemplate.create.action_create') }}</a>
                        <a href="<?php echo BASE_URL; ?>/TicketTemplate/settings" class="btn btn-primary"><i class="fa-solid fa-gears"></i> {{ __('tickettemplate.settings.action_settings') }}</a>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>{{ __('tickettemplate.list.template_title') }}</th>
                        <th style="text-align: right">{{ __('tickettemplate.list.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tpl->get('templates') as $template)
                        <tr>
                            <td>
                                {{ $template['title'] }}
                            </td>
                            <td style="text-align: right;">
                                <a href="<?php echo BASE_URL; ?>/TicketTemplate/updateTemplate/{{ $template['id'] }}" class="btn btn-primary"><i class="fa-solid fa-pen"></i> {{ __('tickettemplate.list.action_update') }}</a>
                                <a href="<?php echo BASE_URL; ?>/TicketTemplate/deleteTemplate/{{ $template['id'] }}" class="btn btn-danger"><i class="fa-solid fa-trash"></i> {{ __('buttons.delete') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
