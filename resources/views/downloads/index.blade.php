@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif
            @if (session('errors'))
            <div class="alert alert-danger">
                <ul>
                    @foreach (session('errors')->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="form-inline">
                <form action='{{ route('downloads.add') }}' method='POST'>
                    @csrf
                    <div class="">
                        <input type='text' name='url' placeholder="url" class="form-control"/>
                        <input type='submit' value='Add Url' class="btn btn-primary">
                    </div>
                </form>
            </div>
            <br/>
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Url</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
                @foreach ($downloads as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->filename }}</td>
                        <td>{{ $item->original_url }}</td>
                        <td>
                            {{ $item->status }}
                            @if ($item->status == 'COMPLETED')
                            <br/>
                            <a href='{{ route('downloads.download', $item->id) }}'>download</a>
                            @endif
                        </td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection
