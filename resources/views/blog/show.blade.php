@extends('layouts.app')

@section('content')
    <livewire:admin.blog.show :slug="$slug" />
@endsection