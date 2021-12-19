<div class="col-md-6">
    <div class="form-group">
        <label for="category_id">Select Category</label>
        <select class="form-control select2 @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
            @if(isset($categories))
            <option value="0" disabled selected>Select Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name ?? ""}}</option>
            @endforeach
            @else
            <option>{{ "Category not found" }}</option>
            @endif
          </select>
        @error('category_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label for="products_name">Products Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="products_name" name="name" value="{{ (isset($edit->name)) ? $edit->name : old('name') }}" autocomplete="licence_no" placeholder="Enter email">
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label for="product_price">Products Price</label>
        <input type="number" class="form-control @error('product_price') is-invalid @enderror" id="product_price" name="product_price" value="{{ (isset($edit->product_price)) ? $edit->product_price : old('product_price') }}" autocomplete="product_price" placeholder="Enter Price" min="0">
        @error('product_price')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label for="product_sku">Products SKU</label>
        <input type="text" class="form-control @error('product_sku') is-invalid @enderror" id="product_sku" name="product_sku" value="{{ (isset($edit->product_sku)) ? $edit->product_sku : old('product_sku') }}" autocomplete="product_sku" placeholder="Enter product SKU">
        @error('product_sku')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="product_description">Products Description</label>
        <textarea type="text" class="form-control @error('product_description') is-invalid @enderror" id="product_description" name="product_description" autocomplete="product_description" placeholder="Enter product description" rows="5">{{ (isset($edit->description)) ? $edit->description : old('product_description') }}</textarea>
        @error('product_description')
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
        <label for="products_image">Products Image</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" id="products_image" name="image"  placeholder="Enter email">
        @error('image')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div></div>
    <div class="col-md-12">
        
        <button type="submit" class="btn btn-primary btn-sm">{{ (isset($edit)) ? "Update" : "Submit" }}</button>
    </div>