@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Search Result</div>

                <div class="card-body">
                        <div >
                            <label for="name">Basics expenses :  </label>
                            <span>{{$bascis_expenses}}</span><br>
                            <label for="name">Administrative expenses :  </label>
                            <span>85</span><br>
                            <label for="name">Value of ID :  </label>
                            <span>15</span><br>
                            <label for="name">Total expenses : </label>
                            <span>{{$total_expenses}}</span><br>
                        </div>
                        
                        <a  href="{{route('home')}}"   
                        class="btn btn-primary" name="back">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
