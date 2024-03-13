@extends($layout)

@section('content')

    <x-global::pageheader :icon="'fa fa-puzzle-piece'">
        <h1>DefaultTicketTemplate Settings</h1>
    </x-global::pageheader>

    <div class="maincontent">
        <?php echo $tpl->displayNotification(); ?>
        <div class="maincontentinner">
            <h4 class="widgettitle title-light"><span class="fa fa-cog"></span>
                Configure default ticket template on project
            </h4>

            <form method="post" action="<?php echo BASE_URL; ?>/DefaultTicketTemplate/settings">
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
                                            $default = $tpl->get('default')
                                        @endphp
                                        <select name="{{$project['projectId']}}">
                                            <option label="{{ $default }}">{{ $default }}</option>
                                            @foreach($tpl->get('templates') as $template)
                                                <option value="{{ $template['title']}}"
                                                @if($project['templateName'] == $template['title'])
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
