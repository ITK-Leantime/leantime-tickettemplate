@extends($layout)

@section('content')

    <x-global::pageheader :icon="'fa fa-puzzle-piece'">
        <h1>{{ __("tickettemplate.settings.page_header") }}</h1>
    </x-global::pageheader>

    <div class="maincontent">
        <?php echo $tpl->displayNotification(); ?>
        <div class="maincontentinner">

            <div class="row" style="margin-left: 0; margin-right: 0;">
                <div class="column">
                    <h4 class="widgettitle title-light"><span class="fa fa-cog"></span>
                        {{ __("tickettemplate.settings.widget_title") }}
                    </h4>
                </div>
                <div class="column">
                    <div class="" style="float:right;">
                        <a href="<?php echo BASE_URL; ?>/DefaultTicketTemplate/listTemplates" class="btn btn-primary"><i class="fa-solid fa-list"></i> {{ __('tickettemplate.list.action_list') }}</a>
                    </div>
                </div>
            </div>

            <form method="post" action="<?php echo BASE_URL; ?>/DefaultTicketTemplate/settings">
                <table class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>{{__("label.project")}}</th>
                            <th>{{__("tickettemplate.settings.template")}}</th>
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
                                            $noDefaultTranslationKey = $tpl->get('noDefaultTranslationKey')
                                        @endphp
                                        <select name="{{$project['projectId']}}">
                                            <option label="{{ __($noDefaultTranslationKey)  }}">{{ $noDefaultTranslationKey }}</option>
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
                <input type="submit" value="{{ __('buttons.save') }}" id="saveBtn" />
            </form>

        </div>
    </div>
@endsection
