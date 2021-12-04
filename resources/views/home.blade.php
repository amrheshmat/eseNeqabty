@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body row ">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div  style="width:52%;margin-right: 10%;margin-left: 16px;">
                    <form action="{{route('member.create')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                        
                        <div class="form-group row">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" name="name" 
                            @if(isset($info)) value="{{$info->name}}" @else value="{{old('name')}}" @endif>
                        </div>
                        <div class="form-group row">
                            <label for="national_id">National ID</label>
                            <input type="number" class="form-control" name="national_id" @if(isset($info)) value="{{$info->national_id}}"  @else value="{{old('national_id')}}" @endif>
                        </div>
                        <div class="form-group row">
                            <label for="childrens">Number of childrens</label>
                            <input type="number" class="form-control" name="childrens" @if(isset($info)) value="{{$info->childrens}}"  @else value="{{old('childrens')}}" @endif>
                        </div>
                        <div class="form-group row">
                            <label for="wives">Number of wives</label>
                            <input type="number" class="form-control" name="wives" @if(isset($info)) value="{{$info->wives}}"  @else value="{{old('wives')}}" @endif>
                        </div>
                        <div class="form-group row">
                            <label for="parents">Number of parents</label>
                            <input type="number" class="form-control" name="parents" @if(isset($info)) value="{{$info->parents}}"  @else value="{{old('parents')}}" @endif>
                        </div>
                        <div class="form-group row">
                            <label for="graduation">Year of  Graduation</label>
                            <input type="number" class="form-control" name="graduation" @if(isset($info)) value="{{$info->graduation}}"  @else value="{{old('graduation')}}" @endif>
                        </div>
                        <button type="submit" class="btn btn-primary" name="publish">Publish</button>
                    </form>
                    </div>
                    <div class="col-xs-offset-2"  style="width:30%;">
                    <form action="{{route('member.search')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                        
                        <div class="form-group row">
                            <label for="search">Serarch by National ID</label>
                            <input type="number" class="form-control" name="search">
                        </div>
                        <button type="submit" class="btn btn-primary" name="publish">Search</button>
                    </form>
                    </div>
                </div>


                
            </div>
        </div>
    </div>
</div>
@endsection
