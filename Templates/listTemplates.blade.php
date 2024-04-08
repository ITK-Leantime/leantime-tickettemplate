@extends($layout)

@section('content')

    <x-global::pageheader :icon="'fa fa-puzzle-piece'">
        <h1>TicketTemplate: List</h1>
    </x-global::pageheader>

    <div class="maincontent">
        <?php echo $tpl->displayNotification(); ?>
        <div class="maincontentinner">
            <div class="row" style="margin-left: 0; margin-right: 0;">
                <div class="column">
                    <h4 class="widgettitle title-light"><span class="fa fa-cog"></span>
                        List of templates
                    </h4>
                </div>
                <div class="column">
                    <div class="" style="float:right;">
                        <a href="<?php echo BASE_URL; ?>/TicketTemplate/createTemplate" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create</a>
                        <a href="<?php echo BASE_URL; ?>/TicketTemplate/settings" class="btn btn-primary"><i class="fa-solid fa-gears"></i> Settings</a>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th style="text-align: right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tpl->get('templates') as $template)
                        <tr>
                            <td>
                                {{ $template['title'] }}
                            </td>
                            <td style="text-align: right;">
                                <a href="<?php echo BASE_URL; ?>/TicketTemplate/updateTemplate/{{ $template['id'] }}" class="btn btn-primary"><i class="fa-solid fa-pen"></i> Update</a>
                                <a href="<?php echo BASE_URL; ?>/TicketTemplate/deleteTemplate/{{ $template['id'] }}" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
