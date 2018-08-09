@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <table class="table mb-0">
                    <tbody>
                    @foreach(auth()->user()->stores as $store)
                    <tr>
                        <td>{{ $store->name }}</td>
                        <td>{{ $store->domain }}</td>
                        <td><a href="stores/{{ $store->id }}">View Store</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection
