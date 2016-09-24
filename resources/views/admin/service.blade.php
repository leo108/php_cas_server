@extends('layouts.admin')

@section('content')
<admin-service-index inline-template>
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('admin.filter')</div>
                    <div class="panel-body">
                        <form class="form-inline" role="form" id="search-form">
                            <div class="form-group">
                                <input name="search" type="text" class="form-control" style="min-width: 300px" v-model="query.search"
                                       placeholder="@lang('admin.service.name')/@lang('admin.service.hosts')"/>
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
                    <table class="table table-striped table-bordered table-hover" id="service-tbl">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('admin.service.name')</th>
                            <th>@lang('admin.service.hosts')</th>
                            <th>@lang('admin.service.enabled')</th>
                            <th>@lang('admin.service.created_at')</th>
                            <th>
                                <button class="btn btn-xs btn-primary" @click="showAdd()">{{ trans('admin.service.add') }}</button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in services">
                            <td>@{{ item.id }}</td>
                            <td>@{{ item.name }}</td>
                            <td>@{{{ displayHosts(item.hosts) }}}</td>
                            <td>@{{{ bool2icon(item.enabled) }}}</td>
                            <td>@{{ item.created_at }}</td>
                            <td>
                                <a href="javascript:void(0)" @click="edit(item)">{{ trans('admin.edit') }}</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="pull-left">@lang('admin.total') {{ $services->total() }}</div>
                    <div class="pull-right">{{ $services->appends($query)->links() }}</div>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('admin.service.add_or_edit')</h4>
                </div>
                <div class="modal-body">
                    <form role="form" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" v-model="editService.id" name="id">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">@lang('admin.service.name')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" v-model="editService.name" name="name"
                                       placeholder="@lang('admin.service.name')" :disabled="isEdit">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">@lang('admin.service.hosts')</label>
                            <div class="col-sm-6">
                            <textarea class="form-control" name="hosts" cols="30" rows="10" v-model="editService.hosts"
                                      placeholder="@lang('admin.service.hosts_placeholder')"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">@lang('admin.service.enabled')</label>
                            <div class="col-sm-6">
                                <input type="checkbox" v-model="editService.enabled" name="enabled"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('common.close')</button>
                    <button type="button" class="btn btn-primary" :disabled="busy" @click="save()">@lang('common.ok')</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</admin-service-index>
@endsection

@section('javascript')
    <script>
        Laravel.data = {
            services: {!! json_encode($services) !!},
            query: {!! json_encode($query) !!}
        };
        Laravel.routes = {
            service: {
                store: '{{ route_uri('admin.service.store') }}',
                update: '{{ route_uri('admin.service.update') }}'
            }
        };
    </script>
    <script src="{{ elixir('js/admin/service/index.js') }}"></script>
@endsection