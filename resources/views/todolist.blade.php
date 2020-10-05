@extends("layouts.main")

@section("title", "Todo list")

@push("styles", "<link rel='stylesheet' href='" . asset("css/todolist.css") . "'>")


@section("content")
  <div class="todo-container">
    <todo-list :current-todos="{{ json_encode($todos) }}"></todo-list>
  </div>
@endsection
