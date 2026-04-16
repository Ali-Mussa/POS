@extends('layouts.master')

@section('title')
    Customer Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Customer Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body text-center">
                <h1>WELCOME,</h1>
                <h2>You are logged in as CUSTOMER</h2>
                <br><br>
            </div>
        </div>
    </div>
</div>
@endsection
