<div class="col-md-6">
    <div class="form-group">
        <label for="category_name">Category Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="category_name" name="name" value="{{ (isset($edit->name)) ? $edit->name : old('name') }}" autocomplete="name" placeholder="Enter nmae">
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
@if(isset($edit) && isset($edit->img_uri))
<div class="col-md-6">
    <div class="form-group">
        <img src="{{ $edit->img_uri }}" style="width:25%" />
    </div>
</div>
@endif
<div class="col-md-6">
    <div class="form-group">
        <label for="category_image">Category Image</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" id="category_image" name="image"  placeholder="Category image">
        @error('image')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="col-md-12">
    <button type="submit" class="btn btn-primary btn-sm">{{ (isset($edit)) ? "Update" : "Submit" }}</button>
</div>