@extends('layouts.admin')

@section('content')
<admin-user-index inline-template>
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('admin.filter')</div>
                    <div class="panel-body">
                        <form class="form-inline" role="form" id="search-form">
                            <div class="form-group">
                                <select class="form-control" name="enabled" v-model="query.enabled">
                                    <option value="">@lang('admin.user.enabled_all')</option>
                                    <option value="0">@lang('admin.user.enabled_no')</option>
                                    <option value="1">@lang('admin.user.enabled_yes')</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input name="search" type="text" class="form-control" style="min-width: 300px" v-model="query.search" placeholder="@lang('admin.user.username')/@lang('admin.user.real_name')/@lang('admin.user.email')" />
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary">@lang('admin.search')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="user-tbl">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('admin.user.username')</th>
                            <th>@lang('admin.user.email')</th>
                            <th>@lang('admin.user.real_name')</th>
                            <th>@lang('admin.user.enabled')</th>
                            <th>@lang('admin.user.admin')</th>
                            <th>@lang('admin.user.created_at')</th>
                            <th>@lang('admin.user.updated_at')</th>
                            <th>
                                <button class="btn btn-xs btn-primary" @click="showAdd()">{{ trans('admin.user.add') }}</button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="item in users">
                            <td>@{{ item.id }}</td>
                            <td>@{{ item.name }}</td>
                            <td>@{{ item.email }}</td>
                            <td>@{{ item.real_name }}</td>
                            <td>@{{{ bool2icon(item.enabled) }}}</td>
                            <td>@{{{ bool2icon(item.admin) }}}</td>
                            <td>@{{ item.created_at }}</td>
                            <td>@{{ item.updated_at }}</td>
                            <td>
                                <button class="btn btn-xs btn-primary" @click="edit(item)">@lang('admin.edit')</button>
                                <button v-if="!isEmpty(item.oauth.plugins)" class="btn btn-xs btn-success" @click="view_oauth(item)">@lang('admin.user.view_oauth')</button>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    <div class="pull-left">@lang('admin.total') {{ $users->total() }}</div>
                    <div class="pull-right">{{ $users->appends($query)->links() }}</div>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->

    <div class="modal fade" id="edit-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('admin.user.add_or_edit')</h4>
                </div>
                <div class="modal-body">
                    <form role="form" class="form-horizontal">
                        <input type="hidden" v-model="edit.id" name="id">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">@lang('admin.user.username')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" v-model="editUser.name" name="name"
                                       placeholder="@lang('admin.user.username')" :disabled="isEdit">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">@lang('admin.user.password')</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" v-model="editUser.password" name="password"
                                       placeholder="@lang('admin.user.password')">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">@lang('admin.user.real_name')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" v-model="editUser.real_name" name="real_name"
                                       placeholder="@lang('admin.user.real_name')">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">@lang('admin.user.email')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" v-model="editUser.email" name="email"
                                       placeholder="@lang('admin.user.email')" :disabled="isEdit">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">@lang('admin.user.enabled')</label>
                            <div class="col-sm-6">
                                <input type="checkbox" class="form-control" v-model="editUser.enabled" name="enabled"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">@lang('admin.user.admin')</label>
                            <div class="col-sm-6">
                                <input type="checkbox" class="form-control" v-model="editUser.admin" name="admin"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('common.close')</button>
                    <button type="button" class="btn btn-primary" :disabled="busy" @click="save">@lang('common.ok')</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="oauth-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('admin.user.view_oauth')</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>@lang('admin.user.oauth.name')</th>
                            <th>@lang('admin.user.oauth.status')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in oauthes">
                            <td>
                                @{{ item.name }}
                            </td>
                            <td>
                                @{{{ bool2icon(item.status) }}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('common.close')</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</admin-user-index>
@endsection

@section('javascript')
    <script>
        Laravel.data = {
            users: {!! json_encode($users) !!},
            query: {!! json_encode($query) !!}
        };
        Laravel.routes = {
            user: {
                store: '{{ route_uri('admin.user.store') }}',
                update: '{{ route_uri('admin.user.update') }}'
            }
        };
    </script>
    <script src="{{ elixir('js/admin/user/index.js') }}"></script>
@endsection