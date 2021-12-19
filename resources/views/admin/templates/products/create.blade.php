@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex">
            <h5 class="title">Crete new Product</h5>
            <a class="btn btn-danger btn-sm ml-auto"
            href="{{ route('product.index') }}">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('product.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    @include('admin.templates.products.form')
                </div>
              </form>
        </div>
    </div>
@endsection