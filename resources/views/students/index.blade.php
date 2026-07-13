@extends('layouts.teste')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <form id="filterForm" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="name" id="filter_name" class="form-control" placeholder="Pesquisar por nome">
            </div>
            <div class="col-md-4">
                <select name="course" id="filter_course" class="form-select">
                    <option value="">-- Todos os cursos --</option>
                    @foreach($courses as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-light-primary flex-grow-1">Filtrar</button>
                <button type="button" id="clearFilters" class="btn btn-outline-secondary">Limpar</button>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-light-success" id="btnNew">Novo Estudante</button>
    </div>
</div>

<div id="studentsTable">
    <div class="text-center py-5">Carregando...</div>
</div>

@include('students._modal')
@endsection

@push('scripts')
<script src="/js/students.js"></script>
@endpush
