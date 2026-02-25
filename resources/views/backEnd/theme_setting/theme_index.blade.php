@extends('backEnd.layouts.master')

@section('content')
<style>
    .color-box {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        border: 3px solid transparent;
        cursor: pointer;
        transition: 0.2s;
    }

    .color-box.selected {
        border: 3px solid #000; /* Selected Border */
        box-shadow: 0 0 10px rgba(0,0,0,0.4);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const boxes = document.querySelectorAll('.color-box');

        boxes.forEach(box => {
            box.addEventListener('click', function () {
                boxes.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    });
</script>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Theme Color Settings</h4>
                </div>

                <div class="card-body bg-light">
                    <form action="{{ route('theme.setting.store') }}" method="POST">
                        @csrf

                        <h4>Select Theme Color</h4>

                        <div style="display:flex; flex-wrap:wrap; gap:15px;">

                            @php
                                $colors = [
                                    '#980404','#038e03','#00009a','#935c09',
                                    '#380289','#06568b','#079370','#840638',
                                    '#000000','#343486','#401a65','#048181','#FFC90D'
                                ];
                            @endphp

                            @foreach($colors as $color)
                                <label class="color-box 
                                    {{ isset($setting->color) && $setting->color == $color ? 'selected' : '' }}"
                                    style="background: {{ $color }};">
                                    
                                    <input type="radio" name="color" value="{{ $color }}"
                                        style="display:none;"
                                        {{ isset($setting->color) && $setting->color == $color ? 'checked' : '' }}>
                                </label>
                            @endforeach

                        </div>

                        <br>
                        <button class="btn btn-primary">Save</button>
                    </form>
                </div>

                <div class="card-footer text-center text-muted small">
                    <em>Powered by <a href="https://webleez.com" target="_blank">Webleez</a></em>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
