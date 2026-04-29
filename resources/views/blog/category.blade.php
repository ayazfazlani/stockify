@extends('layouts.app')

@section('content')
    <livewire:admin.blog.category :slug="$slug" />
@endsection