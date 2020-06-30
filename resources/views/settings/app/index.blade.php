@extends('layouts.app')

@section('content')
<div class="container snippet">
    <div class="row">
    	<div class="col-sm-9">
            <ul class="nav nav-tabs"  id="myTab" role="tablist">
                <li class="nav-item" >
                    <a class="nav-link active" id="general-settings-tab" data-toggle="tab" href="#general-settings" role="tab" aria-controls="general-settings" aria-selected="true">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="database-settings-tab" data-toggle="tab" href="#database-settings" role="tab" aria-controls="database-settings" aria-selected="false">Database</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="mail-settings-tab" data-toggle="tab" href="#mail-settings" role="tab" aria-controls="mail-settings" aria-selected="false">Mail</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="general-settings" role="tabpanel" aria-labelledby="general-settings-tab">
                    <hr>
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                                <label for="user_name" class="col-md-2  text-md-right"><strong>App Name</strong></label>
                                <div class="col-md-6">
                                    <p>{{ $app_settings['name'] }}</p>
                                </div>
                            </div>
                            <div class="row">
                                 <label for="user_name" class="col-md-2  text-md-right"><strong>Debug mode?</strong></label>
                                 <div class="col-md-6">
                                    <p>{{ $app_settings['name']?'Yes':'No' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label for="user_name" class="col-md-2  text-md-right"><strong>Url</strong></label>
                                <div class="col-md-6">
                                    <p>{{ $app_settings['url'] }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label for="user_name" class="col-md-2  text-md-right"><strong>Timezone</strong></label>
                                <div class="col-md-6">
                                    <p>{{ $app_settings['timezone'] }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label for="user_name" class="col-md-2  text-md-right"><strong>Locale</strong></label>
                                <div class="col-md-6">
                                    <p>{{ $app_settings['locale'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/tab-general-settings-->
                <div class="tab-pane fade show" id="database-settings" role="tabpanel" aria-labelledby="database-settings-tab">
                    <hr>
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                                <label for="user_name" class="col-md-2  text-md-right"><strong>Driver</strong></label>
                                <div class="col-md-6">
                                    <p>{{ $db_settings['driver'] }}</p>
                                </div>
                            </div>
                            <div class="row">
                                 <label for="user_name" class="col-md-2  text-md-right"><strong>Url</strong></label>
                                 <div class="col-md-6">
                                    <p>{{ $db_settings['url']}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label for="user_name" class="col-md-2  text-md-right"><strong>Database</strong></label>
                                <div class="col-md-6">
                                    <p>{{ $db_settings['database'] }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label for="user_name" class="col-md-2  text-md-right"><strong>Prefix</strong></label>
                                <div class="col-md-6">
                                    <p>{{ $db_settings['prefix'] }}</p>
                                </div>
                            </div>
                            @if($db_settings['driver'] != 'sqlite')
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Host</strong></label>
                                    <div class="col-md-6">
                                        <p>{{ $db_settings['host']}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Username</strong></label>
                                    <div class="col-md-6">
                                        <p>{{ $db_settings['username'] }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Password</strong></label>
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" value="{{ $db_settings['password'] }}" disabled="disabled"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Charset</strong></label>
                                    <div class="col-md-6">
                                        <p>{{ $db_settings['charset'] }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div><!--/tab-database-settings-->
                <div class="tab-pane fade show" id="mail-settings" role="tabpanel" aria-labelledby="mail-settings-tab">
                    <hr>
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                                <label for="user_name" class="col-md-2  text-md-right"><strong>Transport</strong></label>
                                <div class="col-md-6">
                                    <p>{{ $mail_settings['transport']}}</p>
                                </div>
                            </div>
                            @if($mail_settings['transport'] == 'smtp')
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Host</strong></label>
                                    <div class="col-md-6">
                                        <p>{{ $mail_settings['host']}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Port</strong></label>
                                    <div class="col-md-6">
                                        <p>{{ $mail_settings['port']}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Username</strong></label>
                                    <div class="col-md-6">
                                        <p>{{ $mail_settings['username'] }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Password</strong></label>
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" value="{{ $mail_settings['password'] }}" disabled="disabled"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Encryption</strong></label>
                                    <div class="col-md-6">
                                        <p>{{ $mail_settings['encryption'] }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Timeout</strong></label>
                                    <div class="col-md-6">
                                        <p>{{ $mail_settings['timeout'] }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="user_name" class="col-md-2  text-md-right"><strong>Auth mode</strong></label>
                                    <div class="col-md-6">
                                        <p>{{ $mail_settings['auth_mode'] }}</p>
                                    </div>
                                </div>
                        @endif
                        <div class="row">
                            <label for="user_name" class="col-md-3  text-md-right"><strong>From name</strong></label>
                            <div class="col-md-6">
                                <p>{{ $mail_from['name'] }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <label for="user_name" class="col-md-3  text-md-right"><strong>From address</strong></label>
                            <div class="col-md-6">
                                <p>{{ $mail_from['address'] }}</p>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="alert alert-info col-9" role="alert">
            To change these settings, go to the '.env' file!
        </div>
    </div>
</div>
@endsection