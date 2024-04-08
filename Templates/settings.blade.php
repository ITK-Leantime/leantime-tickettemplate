@extends($layout)

@section('content')

    <x-global::pageheader :icon="'fa fa-puzzle-piece'">
        <h1>TicketTemplate: Settings</h1>
    </x-global::pageheader>

    <div class="maincontent">
        <?php echo $tpl->displayNotification(); ?>
        <div class="maincontentinner">

            <div class="row" style="margin-left: 0; margin-right: 0;">
                <div class="column">
                    <h4 class="widgettitle title-light"><span class="fa fa-cog"></span>
                        Project template settings
                    </h4>
                </div>
                <div class="column">
                    <div class="" style="float:right;">
                        <a href="<?php echo BASE_URL; ?>/TicketTemplate/listTemplates" class="btn btn-primary"><i class="fa-solid fa-list"></i> Templates</a>
                    </div>
                </div>
            </div>

            <form method="post" action="<?php echo BASE_URL; ?>/TicketTemplate/settings">
                <table class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Template</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tpl->get('projects') as $project)
                            <tr>
                                <td>
                                    {{ $project['projectName'] }}
                                </td>
                                <td>
                                    <label>
                                        @php
                                            $noDefault = $tpl->get('noDefault')
                                        @endphp
                                        <select name="{{$project['projectId']}}">
                                            <option label="{{ $noDefault }}">{{ $noDefault }}</option>
                                            @foreach($tpl->get('templates') as $template)
                                                <option value="{{ $template['id']}}"
                                                @if($project['templateId'] == $template['id'])
                                                    {{ 'selected' }}
                                                @endif
                                                >{{ $template['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <input type="submit" value="Save" id="saveBtn" />
            </form>

        </div>
    </div>
@endsection
