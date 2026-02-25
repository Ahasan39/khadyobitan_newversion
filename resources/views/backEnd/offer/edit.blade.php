@extends('backEnd.layouts.master')

@section('content')
<div class="container">
    <h3>Edit Offer</h3>
    <form action="{{ route('offers.update', $offer->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-3">
            <label>Offer Title</label>
            <input type="text" name="title" class="form-control" value="{{ $offer->title }}" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $offer->description }}</textarea>
        </div>

        {{-- Search Box --}}
        <div class="mb-3">
            <label>Search Product</label>
            <input type="text" id="productSearch" class="form-control" placeholder="Search Product...">
            <div id="searchResults" class="border mt-1 p-2" style="display:none; max-height:200px; overflow:auto;"></div>
        </div>

        {{-- Selected Products --}}
        <div class="mb-3">
            <label>Selected Products</label>
            <div id="selectedProducts"></div>
            <div id="hiddenInputs"></div>
        </div>

        <button class="btn btn-success mt-3">Update Offer</button>
    </form>
</div>

<script>
let selectedIds = @json($offer->products->pluck('id')); // Pre-selected products IDs

// Preload selected products
let selectedDiv = document.getElementById('selectedProducts');
let hiddenInputs = document.getElementById('hiddenInputs');
@foreach($offer->products as $p)
selectedDiv.innerHTML += `
    <div class="selected-product d-flex justify-content-between align-items-center p-2 border mb-1" id="selected-{{ $p->id }}">
        {{ $p->name }} <span style="cursor:pointer;color:red" onclick="removeProduct({{ $p->id }})">X</span>
    </div>
`;
hiddenInputs.innerHTML += `<input type="hidden" name="products[]" value="{{ $p->id }}" id="input-{{ $p->id }}">`;
@endforeach

// Search Input
document.getElementById('productSearch').addEventListener('keyup', function(){
    let query = this.value;
    if(query.length < 1){
        document.getElementById('searchResults').style.display = 'none';
        return;
    }

    fetch("{{ route('offer.product.search') }}?q="+query)
        .then(res => res.json())
        .then(data => {
            let resultBox = document.getElementById('searchResults');
            resultBox.innerHTML = "";

            if(data.length === 0){
                resultBox.innerHTML = "<div class='p-2'>No products found</div>";
            } else {
                data.forEach(item => {
                    if(selectedIds.includes(item.id)) return; // skip already selected
                    let div = document.createElement('div');
                    div.className = "p-2 border-bottom";
                    div.style.cursor = "pointer";
                    div.textContent = item.text;
                    div.onclick = function(){ addProduct(item.id, item.text); }
                    resultBox.appendChild(div);
                });
            }

            resultBox.style.display = 'block';
        });
});

// Add Product
function addProduct(id, name){
    if(selectedIds.includes(id)) return;

    selectedIds.push(id);

    let selectedDiv = document.getElementById('selectedProducts');
    let hiddenInputs = document.getElementById('hiddenInputs');

    selectedDiv.innerHTML += `
        <div class="selected-product d-flex justify-content-between align-items-center p-2 border mb-1" id="selected-${id}">
            ${name} <span style="cursor:pointer;color:red" onclick="removeProduct(${id})">X</span>
        </div>
    `;

    hiddenInputs.innerHTML += `<input type="hidden" name="products[]" value="${id}" id="input-${id}">`;

    document.getElementById('searchResults').style.display = 'none';
    document.getElementById('productSearch').value = '';
}

// Remove Product
function removeProduct(id){
    selectedIds = selectedIds.filter(x => x !== id);
    document.getElementById('selected-'+id).remove();
    document.getElementById('input-'+id).remove();
}
</script>
@endsection
