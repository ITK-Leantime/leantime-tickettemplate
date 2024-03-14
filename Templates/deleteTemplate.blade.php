@extends($layout)

@section('content')

    <x-global::pageheader :icon="'fa fa-puzzle-piece'">
        <h1>{{ __("tickettemplate.delete.page_header") }}</h1>
    </x-global::pageheader>

    <div class="maincontent">
        <div class="maincontentinner">
            <?php echo $tpl->displayNotification() ?>
            <h4 class="widget widgettitle"><span class="fa fa-trash"></span>
                {{ __("tickettemplate.delete.widget_title") }}
            </h4>
            <div class="widgetcontent">
                <form method="post">
                    <p>{{ __('tickettemplate.delete.confirm') }}</p><br />
                    <input type="submit" value="{{ __('buttons.yes_delete') }}" name="del" class="button" />
                    <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/DefaultTicketTemplate/listTemplates">{{ __('buttons.back') }}</a>
                </form>
            </div>
        </div>
    </div>

@endsection
