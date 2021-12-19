@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex">
            <h5 class="title">Crete new Category</h5>
            <a class="btn btn-danger btn-sm ml-auto"
            href="{{ route('category.index') }}">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('category.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    @include('admin.templates.category.form')
                </div>
              </form>
        </div>
    </div>
@endsection