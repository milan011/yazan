@extends('layouts.main')

<!-- 面包屑 -->
@section('BreadcrumbTrail')

<section class="content-header">
    <div class="pull-left">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.index')}}">首页</a></li>
            <li><a href="{{route('admin.index')}}">用户列表</a></li>
            <li class="active">添加用户</li>
        </ol>
    </div>
</section>
@endsection
<!-- 主体 -->
@section('content')

@include('layouts.message')

<section class="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
                    <form action="{{route('user.store')}}" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                        <!-- 用户名 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>用户名</label>
                            <div class="col-md-4">
                                <input type="text" name="name" required placeholder="用户名" class="form-control" value="{{old('name')}}"/>
                            </div>
                        </div>
                        <!-- 昵称 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>昵称</label>
                            <div class="col-md-4">
                                <input type="text" required name="nick_name" placeholder="用户昵称" class="form-control" value="{{old('nick_name')}}"/>
                            </div>
                        </div>
                        <!-- 密码 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>登录密码</label>
                            <div class="col-md-4">
                                <input type="password" style="display:none">
                                <input type="text" required name="passwork" placeholder="请输入密码" class="form-control" value=""/>
                            </div>
                        </div>
                        <!-- 密码确认 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>确认密码</label>
                            <div class="col-md-4">
                                <input type="password" required name="password_confirmation" placeholder="再次输入密码" class="form-control" value=""/>
                            </div>
                        </div>
                        <!-- 联系电话 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>联系电话</label>
                            <div class="col-md-4">
                                <input type="text" required name="telephone" placeholder="手机号" class="form-control" value="{{old('telephone')}}" />
                            </div>
                        </div>
                        <!-- 微信号 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>微信号</label>
                            <div class="col-md-4">
                                <input type="text" required name="wx_number" placeholder="微信号" class="form-control" value="{{old('wx_number')}}" />
                            </div>
                        </div>
                        <!-- 邮箱 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>常用邮箱</label>
                            <div class="col-md-4">
                                <input type="password" required name="email" placeholder="手机号" class="form-control" value="{{old('email')}}" />
                            </div>
                        </div>
                        <!-- 用户角色 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">用户角色</label>
                            <div class="col-md-2">
                                <select class="form-control" name="role_id" id="role_id">
                                    <option  value="0">---请选择角色---</option>
                                    @foreach($role_add_allow as $key=>$value)
                                    <option value="{{$key}}" >{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- 用户代理等级 -->
                        <div class="form-group" style="display: none;">
                            <label class="col-md-1 control-label">代理等级</label>
                            <div class="col-md-2">
                                <select class="form-control" name="level" id="role_level">
                                    <option  value="0">---请选择代理等级---</option>
                                    @foreach($agents_level as $key=>$value)
                                    <option value="{{$key}}" >{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- 用户代理上溯 -->
                        <div class="form-group" style="display:none;">
                            <label class="control-label">代理链</label>
                            <div class="controls">
                                <select id="top_category" name="brand_id" style="width:15%">
                                    <option value="0">请选择总代理</option>
                                </select>
                                <select id="second_category" name="car_factory" style="display:none;width:15%;">
                                    <option  value="0">请选择一级</option>
                                </select>
                                <select id="thrid_category" name="category_id" style="display:none;width:15%;">
                                    <option  value="0">请选择二级</option>
                                </select>
                            </div>
                        </div>
                        <!-- 用户地址/收货地址添加 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">地址/收货地址</label>
                            <div class="col-md-4">
                                <input type="password" name="address" placeholder="地址" class="form-control" value="{{old('address')}}" />
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-md-4" style="text-align:center;">
                             	<button type="submit" class="btn btn-sm btn-success">添加</button>
                                <button class="btn" onclick="window.history.go(-1);return false;">返回</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script_content')
<!-- 引入表单验证js -->
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js')}}"></script>
<script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script>
<script>
	$(document).ready(function(){

        $('#role_id').change(function(event) {
            /* 用户角色选择 */
            var role_id= $(this).val();

            console.log(role_id);
        });
	});
</script>
@endsection